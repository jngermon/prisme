<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 */
class CharacterDataDefinitionCalculator extends CharacterDataDefinition
{
    public function getTransformerPriority()
    {
        return 10;
    }

    /**
     * @return string
     */
    public function getDefault()
    {
        return '';
    }

    /**
     * @return string
     * @Assert\NotBlank
     */
    public function getProcessor()
    {
        return $this->getOption('processor', '');
    }

    /**
     * @param string $processor
     */
    public function setProcessor($processor)
    {
        $this->setOption('processor', $processor);

        return $this;
    }

    /**
     * @return string
     * @Assert\NotBlank
     */
    public function getMapping()
    {
        return $this->getOption('mapping', []);
    }

    /**
     * @param string $mapping
     */
    public function setMapping($mapping)
    {
        $this->setOption('mapping', $mapping);

        return $this;
    }

    public function __call($function, $args)
    {
        $mapping = $this->getMapping();

        if (isset($mapping[$function])) {
            return $mapping[$function];
        }
    }
}
