<?php

namespace AppBundle\Twig;

use AppBundle\Domain\Calendar\Model\Date;
use AppBundle\Domain\Calendar\Formatter;
use AppBundle\Entity\Calendar;

class CalendarExtension extends \Twig_Extension
{
    protected $formatter;

    public function __construct(
        Formatter $formatter
    ) {
        $this->formatter = $formatter;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('createDate', [$this, 'createDate']),
        );
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('formatDate', [$this, 'formatDate']),
        );
    }

    public function formatDate(Date $date, $options = [], Calendar $calendar = null)
    {
        $old = $date->getCalendar();

        if ($calendar) {
            $date->setCalendar($calendar);
        }

        $format = $this->formatter->formatDate($date, $options);

        $date->setCalendar($old);

        return $format;
    }

    public function createDate(Calendar $calendar, $year = 0, $month = 1, $day = 1)
    {
        $date = new Date(0, $calendar);

        $date->update($year, $month, $day);

        return $date;
    }

    public function getName()
    {
        return 'calendar';
    }
}
