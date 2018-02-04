<?php

namespace AppBundle\Domain\Calendar\Model;

use AppBundle\Domain\Calendar\Exception\CalendarNotSpecifiedException;

class Date implements \ArrayAccess
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
     * @param integer $nbDaysFromOrigin
     */
    public function setNbDaysFromOrigin($nbDaysFromOrigin)
    {
        $this->nbDaysFromOrigin = $nbDaysFromOrigin;

        return $this;
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

    public function update($year, $month, $day)
    {
        $calendar = $this->getCalendar();

        if (!$calendar) {
            throw new CalendarNotSpecifiedException('No calendar specified');
        }

        if ($month instanceof Month) {
            $month = $month->getNumber();
        }

        $this->nbDaysFromOrigin = $calendar->getDiffDaysWithOrigin();

        $this->nbDaysFromOrigin += $year * $calendar->getNbDays();

        foreach ($calendar->getMonths() as $m) {
            if ($m->getNumber() < $month) {
                $this->nbDaysFromOrigin += $m->getNbDays();
            }
        }

        $this->nbDaysFromOrigin += $day - 1;
    }

    public function offsetExists($offset)
    {
        return in_array($offset, ['nbDaysFromOrigin', 'calendar', 'year', 'month', 'day']);
    }

    public function offsetGet($offset)
    {
        switch($offset) {
            case 'nbDaysFromOrigin':
                return $this->getNbDaysFromOrigin();
            case 'calendar':
                return $this->getCalendar();
            case 'year':
                return $this->getYear();
            case 'month':
                return $this->getMonth();
            case 'day':
                return $this->getDay();
        }
    }

    public function offsetSet($offset, $value)
    {
        switch($offset) {
            case 'nbDaysFromOrigin':
                return $this->setNbDaysFromOrigin($value);
            case 'calendar':
                return $this->setCalendar($value);
        }
    }

    public function offsetUnset($offset)
    {

    }
}
