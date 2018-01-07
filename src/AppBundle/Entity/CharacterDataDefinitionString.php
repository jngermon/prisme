<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 */
class CharacterDataDefinitionString extends CharacterDataDefinition
{
    /**
     * @return integer
     * @Assert\GreaterThanOrEqual(1)
     */
    public function getMaxLength()
    {
        return $this->getOption('maxLength', 100);
    }

    /**
     * @param integer $maxLength
     */
    public function setMaxLength($maxLength)
    {
        $this->setOption('maxLength', $maxLength);

        return $this;
    }
}
