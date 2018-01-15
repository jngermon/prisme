<?php

namespace AppBundle\Controller;

use AppBundle\Domain\LarpParameters\LarpParameters;
use AppBundle\Entity\Larp;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Templating\EngineInterface;

class LarpController extends CRUDController
{
    public function exportParametersAction($id, SerializerInterface $serializer)
    {
        $larp = $this->admin->getSubject();

        $this->admin->checkAccess('export_parameters', $larp);

        $datas = [
            'characterDataSections' => $larp->getCharacterDataSections()->toArray(),
        ];

        $parameters = new LarpParameters();
        $parameters->load($larp);

        $jsonContent = $serializer->serialize($parameters, 'json', ['groups' => ['export'], 'json_encode_options' => JSON_PRETTY_PRINT]);

        $filename = sprintf('larp-parameters.%d.%s.json', $larp->getId(), (new \Datetime())->format('Y-m-d'));

        $response = new Response();
        $response->headers->set('Content-type', 'text/json');
        $response->headers->set('Content-Disposition', 'attachment; filename="'.$filename.'";');

        $response->setContent($jsonContent);

        return $response;
    }

    public function importParametersAction($id, Request $request, SerializerInterface $serializer, EntityManagerInterface $em)
    {
        $larp = $this->admin->getSubject();

        $this->admin->checkAccess('import_parameters', $larp);

        $form = $this->createFormBuilder(null, [
                'translation_domain' => 'Larp',
            ])
            ->add('file', FileType::class)
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            try {
                $data = $form->getData();
                $content = file_get_contents($data['file']->getRealPath());

                $parameters = $serializer->deserialize($content, LarpParameters::class, 'json', ['larp' => $larp]);

                $parameters->applyPersist($em);

                $em->flush();

                $this->addFlash(
                    'sonata_flash_success',
                    $this->trans(
                        'import_parameters.success',
                        [],
                        'Larp'
                    )
                );

                return $this->redirectTo($larp);

            } catch (\Exception $e) {
                $this->addFlash(
                    'sonata_flash_error',
                    $this->trans(
                        'import_parameters.error',
                        ['%errors%' => $e->getMessage()],
                        'Larp'
                    )
                );
            }
        }

        return $this->render('AppBundle:LarpAdmin:import_parameters.html.twig', [
            'action' => 'import_parameters',
            'object' => $larp,
            'form' => $form->createView(),
        ]);
    }
}
