<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Domain\Calendar\Model\Calendar as CalendarInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="calendar")
 */
class Calendar implements LarpRelatedInterface, CalendarInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Larp", inversedBy="calendars")
     */
    protected $larp;

    /**
     * @ORM\Column(type="string", length=64)
     * @Assert\NotBlank()
     * @Assert\Length(max=64)
     * @Groups({"export"})
     */
    protected $name;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"export"})
     */
    protected $diffDaysWithOrigin;

    /**
     * @ORM\OneToMany(targetEntity="CalendarMonth", mappedBy="calendar", cascade={"persist", "remove"})
     * @ORM\OrderBy({"position" = "ASC"})
     * @Groups({"export"})
     */
    protected $months;

    public function __construct()
    {
        $this->diffDaysWithOrigin = 0;
        $this->months = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getName() ?: '-';
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
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
    public function getDiffDaysWithOrigin()
    {
        return $this->diffDaysWithOrigin;
    }

    /**
     * @param integer $diffDaysWithOrigin
     */
    public function setDiffDaysWithOrigin($diffDaysWithOrigin)
    {
        $this->diffDaysWithOrigin = $diffDaysWithOrigin;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getMonths()
    {
        return $this->months;
    }

    /**
     * @param ArrayCollection $months
     */
    public function setMonths($months)
    {
        $this->months = $months;

        return $this;
    }

    /**
     * @return integer
     */
    public function getNbDays()
    {
        $nbDays = 0;
        foreach ($this->getMonths() as $month) {
            $nbDays += $month->getNbDays();
        }

        return $nbDays;
    }
}
