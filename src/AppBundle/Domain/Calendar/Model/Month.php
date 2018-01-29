<?php

namespace AppBundle\Domain\Calendar\Model;

interface Month
{
    public function getNumber();

    public function getName();

    public function getNbDays();

    public function getNameForDate();

    public function getFormatDay();
}
