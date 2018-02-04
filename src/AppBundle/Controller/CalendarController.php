<?php

namespace AppBundle\Controller;

use AppBundle\Domain\Calendar\CalendarProvider;
use AppBundle\Domain\Calendar\Form\Type\CalendarDateType;
use AppBundle\Domain\Calendar\Formatter;
use AppBundle\Domain\Calendar\Model\Date;
use AppBundle\Security\ProfileProvider;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CalendarController extends CRUDController
{
    public function convertorAction(
        Request $request,
        CalendarProvider $provider,
        ProfileProvider $profileProvider
    ) {
        $larp = $profileProvider->getActiveProfile()->getLarp();

        $calendars = $provider->findByLarp($larp);

        if (!count($calendars)) {
            return new RedirectResponse($this->admin->generateUrl('list'));
        }

        $date = new Date(0, $calendars[0]);

        $form = $this->createForm(CalendarDateType::class, $date, [
            'larp' => $larp,
            'label' => 'convertor.date',
        ]);

        $form->handleRequest($request);

        return $this->render('AppBundle:CalendarAdmin:convertor.html.twig', [
            'action' => 'convertor',
            'form' => $form->createView(),
            'date' => $date,
            'calendars' => $calendars,
        ]);
    }

    public function infosAction(
        Request $request,
        CalendarProvider $provider,
        ProfileProvider $profileProvider,
        Formatter $formatter
    ) {
        if (!$request->get('calendar')) {
            return new Response('Parameter "calendar" is missing.', Response::HTTP_BAD_REQUEST);
        }

        $calendar = $provider->findOneById($request->get('calendar'));

        if (!$calendar) {
            return new Response('Calendar not found.', Response::HTTP_NOT_FOUND);
        }

        if ($profileProvider->getActiveProfile()->getLarp() != $calendar->getLarp()) {
            return new Response('This calendar is not related to the current larp.', Response::HTTP_UNAUTHORIZED);
        }

        $nbDaysFromOrigin = $request->get('nbDaysFromOrigin', 0);
        $nbDaysFromOrigin = $nbDaysFromOrigin ?: 0;

        $date = new Date($nbDaysFromOrigin, $calendar);

        if ($request->get('year') != null && $request->get('month') != null && $request->get('day') != null) {
            $date->update($request->get('year'), $request->get('month'), $request->get('day'));
        }

        $datas = $date->getDatas();

        $data = [
            'nbDaysFromOriginInput' => $date->getNbDaysFromOrigin(),
            'year' => $datas['year'],
            'month' => $datas['month']->getId(),
            'day' => $datas['day'],
            'text' => $formatter->formatDate($date),
        ];

        return new JsonResponse($data);
    }
}
