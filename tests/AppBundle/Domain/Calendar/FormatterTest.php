<?php

namespace AppBundle\Domain\Calendar;

use AppBundle\Domain\Calendar\Model\Date;
use AppBundle\Entity\Calendar;
use AppBundle\Entity\CalendarMonth;
use PHPUnit\Framework\TestCase;

class FormatterTest extends TestCase
{
    protected $firstCalendar;

    public function setUp()
    {
        $this->firstCalendar = new Calendar();
        $this->firstCalendar->setDiffDaysWithOrigin(0)
            ->setFormatGlobal('%day% %month% %year%')
            ->setFormatYear(']-Inf,0[%absy% av JC|[0,Inf[%y%')
            ->setFormatDay('{1}1er|]1,Inf[%d%')
            ;

        $months = [
            (new CalendarMonth())
                ->setName('Janvier')
                ->setNumber(1)
                ->setNbDays(31),
            (new CalendarMonth())
                ->setName('Férvier')
                ->setNumber(2)
                ->setNbDays(28),
            (new CalendarMonth())
                ->setName('Mars')
                ->setNumber(2)
                ->setNbDays(31),
        ];

        $this->firstCalendar->setMonths($months);

        $this->secondCalendar = new Calendar();
        $this->secondCalendar->setDiffDaysWithOrigin(0)
            ->setFormatGlobal('%day% %month% %year%')
            ->setFormatYear('de l\'an %y%')
            ->setFormatDay('{1}1er jour |]1,Inf[%d%ième jour')
            ;

        $months = [
            (new CalendarMonth())
                ->setName('Hiver')
                ->setNameForDate('de la saison d\'hiver')
                ->setNumber(1)
                ->setNbDays(50),
            (new CalendarMonth())
                ->setName('Eté')
                ->setNameForDate('de la saison d\'été')
                ->setFormatDay('{21}Fête de la musique')
                ->setNumber(2)
                ->setNbDays(50),
        ];

        $this->secondCalendar->setMonths($months);
    }

    public function testFormatDate()
    {
        $formatter = new Formatter();

        $this->assertEquals('1 1 0', $formatter->formatDate(new Date(0, $this->firstCalendar), ['short' => true]));
        $this->assertEquals('1 1 1', $formatter->formatDate(new Date(90, $this->firstCalendar), ['short' => true]));
        $this->assertEquals('1 1 -1', $formatter->formatDate(new Date(-90, $this->firstCalendar), ['short' => true]));

        $this->assertEquals('15 2 0', $formatter->formatDate(new Date(45, $this->firstCalendar), ['short' => true]));

        $this->assertEquals('0-2-15', $formatter->formatDate(new Date(45, $this->firstCalendar), ['short' => true, 'format' => '%year%-%month%-%day%']));

        $this->assertEquals('1er Janvier 0', $formatter->formatDate(new Date(0, $this->firstCalendar)));
        $this->assertEquals('2 Janvier 0', $formatter->formatDate(new Date(1, $this->firstCalendar)));
        $this->assertEquals('31 Mars 1 av JC', $formatter->formatDate(new Date(-1, $this->firstCalendar)));


        $this->assertEquals('1er jour de la saison d\'hiver de l\'an 2', $formatter->formatDate(new Date(200, $this->secondCalendar)));
        $this->assertEquals('11ième jour de la saison d\'été de l\'an 2', $formatter->formatDate(new Date(260, $this->secondCalendar)));
        $this->assertEquals('Fête de la musique de la saison d\'été de l\'an 2', $formatter->formatDate(new Date(270, $this->secondCalendar)));
    }
}
