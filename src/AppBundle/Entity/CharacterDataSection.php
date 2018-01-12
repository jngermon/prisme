<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Gedmo\Sortable\Entity\Repository\SortableRepository")
 */
class CharacterDataSection implements LarpRelatedInterface
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
     */
    protected $position;

    /**
     * @ORM\ManyToOne(targetEntity="Larp", inversedBy="characterDataSections")
     * @Gedmo\SortableGroup
     */
    protected $larp;

    /**
     * @ORM\OneToMany(targetEntity="CharacterDataDefinition", mappedBy="section")
     * @ORM\OrderBy({"position" = "ASC"})
     */
    protected $characterDataDefinitions;

    /**
     * @ORM\Column(name="label", type="string", length=255)
     * @Assert\NotBlank()
     */
    protected $label;

    /**
     * @ORM\Column(name="class", type="integer")
     * @Assert\GreaterThanOrEqual(1)
     * @Assert\LessThanOrEqual(12)
     */
    protected $size;

    public function __construct()
    {
        $this->size = 6;
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
     * @return integer
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param integer $size
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getCharacterDataDefinitions()
    {
        return $this->characterDataDefinitions;
    }
}
