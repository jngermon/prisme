<?php

namespace AppBundle\Domain\Calendar;

use AppBundle\Domain\Calendar\Model\Date;
use Symfony\Component\Translation\Exception\InvalidArgumentException;
use Symfony\Component\Translation\Formatter\MessageFormatter;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Formatter
{
    public function formatDate(Date $date, $options = [])
    {
        $options = $this->createOptionsResolver()->resolve($options);

        try {
            $datas = $date->getDatas();
        } catch (Exception\CalendarException $e) {
            return $e->getMessage();
        }

        $messageFormatter = new MessageFormatter();

        $format = $options['format'] ?: $date->getCalendar()->getFormatGlobal();

        if ($options['short']) {
            return $messageFormatter->format($format, null, [
                '%year%' => $datas['year'],
                '%month%' => $datas['month']->getNumber(),
                '%day%' => $datas['day'],
            ]);
        }

        try {
            $year = $messageFormatter->choiceFormat($date->getCalendar()->getFormatYear(), $datas['year'], null, ['%y%' => $datas['year'], '%absy%' => abs($datas['year'])]);
        } catch (InvalidArgumentException $e) {
            $year = $datas['year'];
        }

        $month = $datas['month']->getNameForDate() ?: $datas['month']->getName();

        $day = '';
        if ($datas['month']->getFormatDay()) {
            try {
                $day = $messageFormatter->choiceFormat($datas['month']->getFormatDay(), $datas['day'], null, ['%d%' => $datas['day']]);
            } catch (InvalidArgumentException $e) {
            }
        }

        if (!$day) {
            try {
                $day = $messageFormatter->choiceFormat($date->getCalendar()->getFormatDay(), $datas['day'], null, ['%d%' => $datas['day']]);
            } catch (InvalidArgumentException $e) {
                $day = $datas['day'];
            }
        }

        return $messageFormatter->format($format, null, [
            '%year%' => $year,
            '%month%' => $month,
            '%day%' => $day,
        ]);
    }

    protected function createOptionsResolver()
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            'short' => false,
            'format' => null,
        ]);

        return $resolver;
    }
}
