<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Gedmo\Sortable\Entity\Repository\SortableRepository")
 */
class CalendarMonth
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
     * @ORM\ManyToOne(targetEntity="Calendar", inversedBy="months")
     * @Gedmo\SortableGroup
     */
    protected $calendar;

    /**
     * @ORM\Column(name="name", type="string", length=64, nullable=true)
     * @Assert\NotBlank()
     * @Assert\Length(max=64)
     * @Groups({"export"})
     */
    protected $name;

    /**
     * @ORM\Column(type="integer")
     * @Assert\GreaterThan(1)
     * @Groups({"export"})
     */
    protected $nbDays;

    public function __construct()
    {
        $this->position = -1;
    }

    public function __toString()
    {
        return $this->name ?: '-';
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
     * @return Calendar
     */
    public function getCalendar()
    {
        return $this->calendar;
    }

    /**
     * @param Calendar $calendar
     */
    public function setCalendar($calendar)
    {
        $this->calendar = $calendar;

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
     * @return integer
     */
    public function getNbDays()
    {
        return $this->nbDays;
    }

    /**
     * @param integer $nbDays
     */
    public function setNbDays($nbDays)
    {
        $this->nbDays = $nbDays;

        return $this;
    }
}
