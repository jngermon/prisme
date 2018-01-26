<?php

namespace AppBundle\Domain\Calendar\Model;

use AppBundle\Domain\Calendar\Exception\CalendarException;
use AppBundle\Entity\Calendar;
use AppBundle\Entity\CalendarMonth;
use PHPUnit\Framework\TestCase;

class ManipulatorTest extends TestCase
{
    public function test()
    {
        $calendar = new Calendar();
        $calendar->setDiffDaysWithOrigin(0);

        $months = [];
        for ($i = 0; $i < 12; $i++) {
            $month = new CalendarMonth();
            $month->setPosition($i);
            $month->setName('Mount'.($i+1));
            $month->setNbDays(25);
            $months[] = $month;
        }

        $calendar->setMonths($months);

        $calendar2 = new Calendar();
        $calendar2->setDiffDaysWithOrigin(350);

        $months = [];
        for ($i = 0; $i < 6; $i++) {
            $month = new CalendarMonth();
            $month->setPosition($i);
            $month->setName('Mount'.($i+1));
            $month->setNbDays(50);
            $months[] = $month;
        }

        $calendar2->setMonths($months);

        $this->controleDatas(new Date(0, $calendar), 0, 1, 1);
        $this->controleDatas(new Date(24, $calendar), 0, 1, 25);
        $this->controleDatas(new Date(25, $calendar), 0, 2, 1);
        $this->controleDatas(new Date(299, $calendar), 0, 12, 25);
        $this->controleDatas(new Date(300, $calendar), 1, 1, 1);
        $this->controleDatas(new Date(810, $calendar), 2, 9, 11);
        $this->controleDatas(new Date(1550, $calendar), 5, 3, 1);

        $calendar->setDiffDaysWithOrigin(300);

        $this->controleDatas(new Date(0, $calendar), -1, 1, 1);
        $this->controleDatas(new Date(24, $calendar), -1, 1, 25);
        $this->controleDatas(new Date(25, $calendar), -1, 2, 1);
        $this->controleDatas(new Date(299, $calendar), -1, 12, 25);
        $this->controleDatas(new Date(300, $calendar), 0, 1, 1);
        $this->controleDatas(new Date(810, $calendar), 1, 9, 11);
        $this->controleDatas(new Date(1550, $calendar), 4, 3, 1);

        $this->controleDatas(new Date(0, $calendar2), -2, 6, 1);
        $this->controleDatas(new Date(24, $calendar2), -2, 6, 25);
        $this->controleDatas(new Date(25, $calendar2), -2, 6, 26);
        $this->controleDatas(new Date(299, $calendar2), -1, 5, 50);
        $this->controleDatas(new Date(300, $calendar2), -1, 6, 1);
        $this->controleDatas(new Date(810, $calendar2), 1, 4, 11);
        $this->controleDatas(new Date(1550, $calendar2), 4, 1, 1);
    }

    /**
     *
     */
    public function testNoCalendar()
    {
        $this->expectException(CalendarException::class);

        $date = new Date(0);

        $date->getDatas();
    }

    protected function controleDatas($date, $year, $month, $day)
    {
        $datas = $date->getDatas();

        $this->assertEquals($year, $datas['year']);
        $this->assertEquals($month, $datas['month']->getNumber());
        $this->assertEquals($day, $datas['day']);

        $this->assertEquals($year, $date->getYear());
        $this->assertEquals($month, $date->getMonth()->getNumber());
        $this->assertEquals($day, $date->getDay());
    }
}
