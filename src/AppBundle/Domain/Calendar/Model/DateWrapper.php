<?php

namespace AppBundle\Domain\Calendar\Model;

class DateWrapper
{
    protected $year;

    protected $month;

    protected $day;

    protected $date;

    public function __construct(Date $date)
    {
        $this->date = $date;

        $this->year = 0;
        $this->month = 1;
        $this->day = 1;

        $this->update();
    }

    public function update()
    {
        $this->date->update($this->year, $this->month, $this->day);

        $datas = $this->date->getDatas();
        $this->year = $datas['year'];
        $this->month = $datas['month'];
        $this->day = $datas['day'];
    }

    /**
     * @return Date
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @return integer
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * @param integer $year
     */
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * @return Month
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * @param Month $month
     */
    public function setMonth($month)
    {
        $this->month = $month;

        return $this;
    }

    /**
     * @return integer
     */
    public function getDay()
    {
        return $this->day;
    }

    /**
     * @param integer $day
     */
    public function setDay($day)
    {
        $this->day = $day;

        return $this;
    }
}
