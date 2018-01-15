<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @UniqueEntity(fields={"larp", "name"},
 *   errorPath="name"
 * )
 */
class CharacterDataDefinitionEnumCategory implements LarpRelatedInterface
{
    /**
     * @ORM\Id @ORM\GeneratedValue @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(name="name", type="string", length=64)
     * @Groups({"export"})
     */
    protected $name;

    /**
     * @ORM\ManyToOne(targetEntity="Larp", inversedBy="characterDataDefinitionEnumCategories")
     */
    protected $larp;

    /**
     * @ORM\Column(name="label", type="string", length=255)
     * @Groups({"export"})
     */
    protected $label;

    /**
     * @ORM\OneToMany(targetEntity="CharacterDataDefinitionEnumCategoryItem", mappedBy="category", cascade={"persist", "remove"})
     * @Groups({"export"})
     */
    protected $items;

    /**
     * @ORM\OneToMany(targetEntity="CharacterDataDefinitionEnum", mappedBy="category", cascade={"remove"})
     */
    protected $enums;

    public function __toString()
    {
        return $this->label ?: '-';
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Larp
     */
    public function getLarp()
    {
        return $this->larp;
    }

    /**
     * @param Larp $larp
     */
    public function setLarp($larp)
    {
        $this->larp = $larp;

        return $this;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param string $label
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param ArrayCollection $items
     */
    public function setItems($items)
    {
        $this->items = $items;

        return $this;
    }
}
