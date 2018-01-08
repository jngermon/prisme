<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class CharacterDataDefinitionInteger extends CharacterDataDefinition
{
    /**
     * @return integer
     */
    public function getDefault()
    {
        return $this->getOption('default', 0);
    }

    /**
     * @param integer $default
     */
    public function setDefault($default)
    {
        $this->setOption('default', $default);

        return $this;
    }
}
