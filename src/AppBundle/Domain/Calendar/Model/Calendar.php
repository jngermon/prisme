<?php

namespace AppBundle\Domain\Calendar\Model;

interface Calendar
{
    public function getDiffDaysWithOrigin();

    public function getMonths();

    public function getNbDays();
}
