<?php

namespace AppBundle\Entity;

use AppBundle\Domain\Calendar\Model\Date;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class CharacterDataDefinitionCalendarDate extends CharacterDataDefinition
{
    /**
     * @return string
     */
    public function getDefault()
    {
        $calendar = $this->getLarp()->getCalendars()->get(0);
        return new Date(0, $calendar);
    }
}
