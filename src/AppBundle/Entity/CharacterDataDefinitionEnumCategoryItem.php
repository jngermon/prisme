<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Gedmo\Sortable\Entity\Repository\SortableRepository")
 */
class CharacterDataDefinitionEnumCategoryItem
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @Gedmo\SortablePosition
     * @ORM\Column(name="position", type="integer")
     * @Groups({"export"})
     */
    protected $position;

    /**
     * @ORM\ManyToOne(targetEntity="CharacterDataDefinitionEnumCategory", inversedBy="items")
     * @Gedmo\SortableGroup
     */
    protected $category;

    /**
     * @ORM\Column(name="name", type="string", length=64, nullable=true)
     * @Groups({"export"})
     */
    protected $name;

    /**
     * @ORM\Column(name="label", type="string", length=255)
     * @Assert\NotBlank()
     * @Groups({"export"})
     */
    protected $label;

    public function __construct()
    {
        $this->position = -1;
    }

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
     * @return integer
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param integer $position
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
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
}
