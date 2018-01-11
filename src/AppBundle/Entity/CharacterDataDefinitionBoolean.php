<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 */
class CharacterDataDefinitionBoolean extends CharacterDataDefinition
{
    /**
     * @return string
     */
    public function getDefault()
    {
        return $this->getOption('default', false);
    }

    /**
     * @param string $default
     */
    public function setDefault($default)
    {
        $this->setOption('default', $default);

        return $this;
    }

    /**
     * @return string
     */
    public function getTrue()
    {
        return $this->getOption('true', '1');
    }

    /**
     * @param string $true
     */
    public function setTrue($true)
    {
        $this->setOption('true', $true);

        return $this;
    }

    /**
     * @return string
     */
    public function getFalse()
    {
        return $this->getOption('false', '0');
    }

    /**
     * @param string $false
     */
    public function setFalse($false)
    {
        $this->setOption('false', $false);

        return $this;
    }
}
