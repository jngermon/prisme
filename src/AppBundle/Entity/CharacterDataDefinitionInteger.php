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

    /**
     * @return integer
     */
    public function getMinValue()
    {
        return $this->getOption('min', null);
    }

    /**
     * @param integer $min
     */
    public function setMinValue($min)
    {
        $this->setOption('min', $min);

        return $this;
    }

    /**
     * @return integer
     */
    public function getMaxValue()
    {
        return $this->getOption('max', null);
    }

    /**
     * @param integer $max
     */
    public function setMaxValue($max)
    {
        $this->setOption('max', $max);

        return $this;
    }
}
