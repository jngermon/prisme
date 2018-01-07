<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Gedmo\Sortable\Entity\Repository\SortableRepository")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({
 *   "string" = "CharacterDataDefinitionString",
 *   "integer" = "CharacterDataDefinitionInteger",
 * })
 * @UniqueEntity(fields={"larp", "name"},
 *   entityClass="AppBundle\Entity\CharacterDataDefinition",
 *   errorPath="name"
 * )
 */
abstract class CharacterDataDefinition implements LarpRelatedInterface
{
    /**
     * @ORM\Id @ORM\GeneratedValue @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(name="name", type="string", length=64)
     */
    protected $name;

    /**
     * @Gedmo\SortablePosition
     * @ORM\Column(name="position", type="integer")
     */
    protected $position;

    /**
     * @ORM\ManyToOne(targetEntity="Larp")
     */
    protected $larp;

    /**
     * @ORM\Column(name="options", type="json")
     */
    protected $options;

    /**
     * @ORM\Column(name="label", type="string", length=255)
     */
    protected $label;

    /**
     * @ORM\Column(name="min", type="integer")
     * @Assert\GreaterThanOrEqual(0)
     */
    protected $min;

    /**
     * @ORM\Column(name="max", type="integer")
     * @Assert\GreaterThanOrEqual(propertyPath="min")
     * @Assert\GreaterThanOrEqual(1)
     */
    protected $max;

    public function __construct()
    {
        $this->min = 0;
        $this->max = 1;
        $this->options = [];
        $this->position = -1;
    }

    public function __toString()
    {
        return $this->label ?: '-';
    }

    /**
     * @return string
     */
    //abstract public function getType();
    public function getType()
    {
        return  get_class($this);
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
     * @return Object
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param Object $options
     */
    public function setOptions($options)
    {
        $this->options = $options;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getOption($name, $default = null)
    {
        return isset($this->options[$name]) ? $this->options[$name] : $default;
    }

    /**
     * @param mixed $option
     */
    public function setOption($name, $value)
    {
        $this->options[$name] = $value;

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
    public function getMin()
    {
        return $this->min;
    }

    /**
     * @param integer $min
     */
    public function setMin($min)
    {
        $this->min = $min;

        return $this;
    }

    /**
     * @return integer
     */
    public function getMax()
    {
        return $this->max;
    }

    /**
     * @param integer $max
     */
    public function setMax($max)
    {
        $this->max = $max;

        return $this;
    }
}