<?php

namespace AppBundle\Controller;

use AppBundle\Domain\Calendar\CalendarProvider;
use AppBundle\Domain\Calendar\Model\Date;
use AppBundle\Domain\Calendar\Model\DateWrapper;
use AppBundle\Domain\LarpParameters\LarpParameters;
use AppBundle\Entity\Calendar;
use AppBundle\Entity\CalendarMonth;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CalendarController extends CRUDController
{
    public function convertorAction(Request $request, CalendarProvider $provider)
    {
        $calendar = $this->admin->getSubject();

        $this->admin->checkAccess('convertor', $calendar);

        $dateWrapper = new DateWrapper(new Date(0, $calendar));

        $form = $this->createFormForConvertor($dateWrapper);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $dateWrapper->update();
            $datas = $dateWrapper->getDate()->getDatas();

            $request->query->set('day', $datas['day']);
            $request->query->set('month', $datas['month']);
            $request->query->set('year', $datas['year']);

            // Re-create form with correct values for date
            $form = $this->createFormForConvertor($dateWrapper);
        }

        $calendars = $provider->findByLarp($calendar->getLarp());

        return $this->render('AppBundle:CalendarAdmin:convertor.html.twig', [
            'action' => 'convertor',
            'object' => $calendar,
            'form' => $form->createView(),
            'date' => $dateWrapper->getDate(),
            'calendars' => $calendars,
        ]);
    }

    protected function createFormForConvertor(DateWrapper $dateWrapper)
    {
        $calendar = $dateWrapper->getDate()->getCalendar();

        return $this->createFormBuilder($dateWrapper, [
                'translation_domain' => 'Calendar',
            ])
            ->add('year', IntegerType::class)
            ->add('month', EntityType::class, [
                'class' => CalendarMonth::class,
                'query_builder' => function (EntityRepository $er) use ($calendar) {
                    return $er->createQueryBuilder('m')
                        ->andWhere('m.calendar = :calendar')
                        ->setParameter('calendar', $calendar)
                        ->orderBy('m.position', 'ASC');
                },
            ])
            ->add('day', IntegerType::class)
            ->getForm()
            ;
    }
}
