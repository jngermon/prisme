<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 */
class CharacterDataDefinitionEnum extends CharacterDataDefinition
{
    /**
     * @ORM\ManyToOne(targetEntity="CharacterDataDefinitionEnumCategory", inversedBy="enums")
     */
    protected $category;

    /**
     * @return string
     */
    public function getDefault()
    {
        return null;
    }

    /**
     * @return CharacterDataDefinitionEnumCategory
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param CharacterDataDefinitionEnumCategory $category
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @Groups({"export"})
     */
    public function getCategoryName()
    {
        return $this->category ? $this->category->getName() : '';
    }
}
