<?php

namespace ExternalBundle\Controller;

use Doctrine\ORM\EntityManager;
use ExternalBundle\Domain\Synchronizer\SynchronizerInterface;
use ExternalBundle\Entity\Synchronization;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * @Route(service="external.controller.sync")
 */
class SyncController
{
    protected $session;

    protected $translator;

    protected $router;

    protected $em;

    protected $serializer;

    public function __construct(
        SessionInterface $session,
        TranslatorInterface $translator,
        RouterInterface $router,
        EntityManager $em,
        SerializerInterface $serializer
    ) {
        $this->session = $session;
        $this->translator = $translator;
        $this->router = $router;
        $this->em = $em;
        $this->serializer = $serializer;
    }

    /**
     * @Route("/synchronize", name="synchronize")
     */
    public function syncAction(Request $request)
    {
        $options = [
            'class' => $request->get('class'),
            'ids' => $request->get('ids'),
        ];

        $synchronization = new Synchronization();
        $synchronization->setOptions($this->serializer->encode($options, 'json'));
        $this->em->persist($synchronization);
        $this->em->flush();

        exec("/srv/bin/console external:synchronize --continue >> /srv/var/logs/synchronize.log &");

        $this->session->getFlashBag()->add('success', $this->translator->trans('process.persist', [], 'Sync'));

        if ($referer = $request->headers->get('referer')) {
            return new RedirectResponse($referer);
        }

        return new RedirectResponse($this->router->generate('sonata_admin_dashboard'));
    }
}
