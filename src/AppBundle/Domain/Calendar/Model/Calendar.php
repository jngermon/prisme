<?php

namespace AppBundle\Domain\Calendar\Model;

interface Calendar
{
    public function getDiffDaysWithOrigin();

    public function getMonths();

    public function getNbDays();

    public function getFormatGlobal();

    public function getFormatYear();

    public function getFormatDay();
}
