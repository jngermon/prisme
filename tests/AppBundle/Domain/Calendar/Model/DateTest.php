<?php

namespace AppBundle\Domain\Calendar\Model;

use AppBundle\Domain\Calendar\Exception\CalendarException;
use AppBundle\Entity\Calendar;
use AppBundle\Entity\CalendarMonth;
use PHPUnit\Framework\TestCase;

class ManipulatorTest extends TestCase
{
    protected $firstCalendar;

    protected $secondCalendar;

    public function setUp()
    {
        $this->firstCalendar = new Calendar();
        $this->firstCalendar->setDiffDaysWithOrigin(0);

        $months = [];
        for ($i = 0; $i < 12; $i++) {
            $month = new CalendarMonth();
            $month->setPosition($i);
            $month->setName('Mount'.($i+1));
            $month->setNbDays(25);
            $months[] = $month;
        }

        $this->firstCalendar->setMonths($months);

        $this->secondCalendar = new Calendar();
        $this->secondCalendar->setDiffDaysWithOrigin(350);

        $months = [];
        for ($i = 0; $i < 6; $i++) {
            $month = new CalendarMonth();
            $month->setPosition($i);
            $month->setName('Mount'.($i+1));
            $month->setNbDays(50);
            $months[] = $month;
        }

        $this->secondCalendar->setMonths($months);

        $this->thirdCalendar = new Calendar();
        $this->thirdCalendar->setDiffDaysWithOrigin(0);

        $months = [
            (new CalendarMonth())
                ->setName('Month1')
                ->setNumber(1)
                ->setNbDays(30),
            (new CalendarMonth())
                ->setName('Month2')
                ->setNumber(2)
                ->setNbDays(10),
            (new CalendarMonth())
                ->setName('Month3')
                ->setNumber(3)
                ->setNbDays(30),
        ];

        $this->thirdCalendar->setMonths($months);
    }

    public function test()
    {
        $this->controleDatas(new Date(0, $this->firstCalendar), 0, 1, 1);
        $this->controleDatas(new Date(24, $this->firstCalendar), 0, 1, 25);
        $this->controleDatas(new Date(25, $this->firstCalendar), 0, 2, 1);
        $this->controleDatas(new Date(299, $this->firstCalendar), 0, 12, 25);
        $this->controleDatas(new Date(300, $this->firstCalendar), 1, 1, 1);
        $this->controleDatas(new Date(810, $this->firstCalendar), 2, 9, 11);
        $this->controleDatas(new Date(1550, $this->firstCalendar), 5, 3, 1);

        $this->firstCalendar->setDiffDaysWithOrigin(300);

        $this->controleDatas(new Date(0, $this->firstCalendar), -1, 1, 1);
        $this->controleDatas(new Date(24, $this->firstCalendar), -1, 1, 25);
        $this->controleDatas(new Date(25, $this->firstCalendar), -1, 2, 1);
        $this->controleDatas(new Date(299, $this->firstCalendar), -1, 12, 25);
        $this->controleDatas(new Date(300, $this->firstCalendar), 0, 1, 1);
        $this->controleDatas(new Date(810, $this->firstCalendar), 1, 9, 11);
        $this->controleDatas(new Date(1550, $this->firstCalendar), 4, 3, 1);

        $this->controleDatas(new Date(0, $this->secondCalendar), -2, 6, 1);
        $this->controleDatas(new Date(24, $this->secondCalendar), -2, 6, 25);
        $this->controleDatas(new Date(25, $this->secondCalendar), -2, 6, 26);
        $this->controleDatas(new Date(299, $this->secondCalendar), -1, 5, 50);
        $this->controleDatas(new Date(300, $this->secondCalendar), -1, 6, 1);
        $this->controleDatas(new Date(810, $this->secondCalendar), 1, 4, 11);
        $this->controleDatas(new Date(1550, $this->secondCalendar), 4, 1, 1);
    }

    public function testNoCalendar()
    {
        $this->expectException(CalendarException::class);

        $date = new Date(0);

        $date->getDatas();
    }

    public function testUpdate()
    {
        $date = new Date(0, $this->firstCalendar);
        $this->controleDatas($date, 0, 1, 1);

        $date->update(1, 2, 7);
        $this->controleDatas($date, 1, 2, 7);

        $date = new Date(0, $this->thirdCalendar);
        $this->controleDatas($date, 0, 1, 1);

        $date->update(1, 2, 4);
        $this->controleDatas($date, 1, 2, 4);

        $date->update(1, 2, 15);
        $this->controleDatas($date, 1, 3, 5);
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
