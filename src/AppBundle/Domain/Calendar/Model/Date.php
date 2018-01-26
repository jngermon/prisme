<?php

namespace AppBundle\Domain\Calendar\Model;

use AppBundle\Domain\Calendar\Exception\CalendarNotSpecifiedException;

class Date
{
    protected $nbDaysFromOrigin;

    protected $calendar;

    public function __construct(
        $nbDaysFromOrigin,
        Calendar $calendar = null
    ) {
        $this->nbDaysFromOrigin = $nbDaysFromOrigin;
        $this->calendar = $calendar;
    }

    /**
     * @return integer
     */
    public function getNbDaysFromOrigin()
    {
        return $this->nbDaysFromOrigin;
    }

    /**
     * @return Calendar
     */
    public function getCalendar()
    {
        return $this->calendar;
    }

    /**
     * @param Calendar $calendar
     */
    public function setCalendar($calendar)
    {
        $this->calendar = $calendar;

        return $this;
    }

    public function getDay()
    {
        return $this->getDatas()['day'];
    }

    public function getMonth()
    {
        return $this->getDatas()['month'];
    }

    public function getYear()
    {
        return $this->getDatas()['year'];
    }

    public function getDatas()
    {
        $calendar = $this->getCalendar();

        if (!$calendar) {
            throw new CalendarNotSpecifiedException('No calendar specified');
        }

        $nbDaysFromCalendarOrigin = $this->getNbDaysFromOrigin() - $calendar->getDiffDaysWithOrigin();

        $year = floor($nbDaysFromCalendarOrigin / $calendar->getNbDays());

        $nbDaysFromCalendarOrigin -= $year * $calendar->getNbDays();

        $month = null;
        foreach ($calendar->getMonths() as $m) {
            if ($m->getNbDays() > $nbDaysFromCalendarOrigin) {
                $month = $m;
                break;
            }
            $nbDaysFromCalendarOrigin -= $m->getNbDays();
        }

        $day = $nbDaysFromCalendarOrigin + 1;

        return [
            'day' => $day,
            'month' => $month,
            'year' => $year,
        ];
    }
}
