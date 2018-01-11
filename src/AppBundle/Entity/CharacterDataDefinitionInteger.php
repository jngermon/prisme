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
    public function getMin()
    {
        return $this->getOption('min', null);
    }

    /**
     * @param integer $min
     */
    public function setMin($min)
    {
        $this->setOption('min', $min);

        return $this;
    }

    /**
     * @return integer
     */
    public function getMax()
    {
        return $this->getOption('max', null);
    }

    /**
     * @param integer $max
     */
    public function setMax($max)
    {
        $this->setOption('max', $max);

        return $this;
    }

    /**
     * @return string
     */
    public function getSingular()
    {
        return $this->getOption('singular', '');
    }

    /**
     * @param string $singular
     */
    public function setSingular($singular)
    {
        $this->setOption('singular', $singular);

        return $this;
    }

    /**
     * @return string
     */
    public function getPlural()
    {
        return $this->getOption('plural', '');
    }

    /**
     * @param string $plural
     */
    public function setPlural($plural)
    {
        $this->setOption('plural', $plural);

        return $this;
    }
}
