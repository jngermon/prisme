<?php

namespace AppBundle\Controller;

use AppBundle\Domain\Calendar\CalendarProvider;
use AppBundle\Domain\Calendar\Model\Date;
use AppBundle\Domain\CharacterDataDefinition\Calculator\CalculatorAge;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * @Route("/calculator")
 */
class CalculatorController
{
    /**
     * @Route("/age", name="calculator.age")
     */
    public function ageAction(Request $request, CalendarProvider $provider, CalculatorAge $calculator)
    {
        if (!$request->get('nbDaysFromOrigin')) {
            return new Response('Parameter "nbDaysFromOrigin" is missing.', Response::HTTP_BAD_REQUEST);
        }

        $calendar = $provider->findOneById($request->get('calendar'));

        if (!$calendar) {
            return new Response('Parameter "calendar" is missing.', Response::HTTP_BAD_REQUEST);
        }

        $date = new Date($request->get('nbDaysFromOrigin'), $calendar);

        return new JsonResponse(['text' => $calculator->getAgeFor($date)]);
    }
}
