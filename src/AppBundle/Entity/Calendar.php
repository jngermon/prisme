<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="calendar")
 */
class Calendar implements LarpRelatedInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Larp", inversedBy="characters")
     */
    protected $larp;

    /**
     * @ORM\Column(type="string", length=64)
     * @Assert\NotBlank()
     * @Assert\Length(max=64)
     */
    protected $name;

    /**
     * @ORM\Column(type="integer")
     */
    protected $diffDaysWithOrigin;

    /**
     * @ORM\OneToMany(targetEntity="CalendarMonth", mappedBy="calendar")
     * @ORM\OrderBy({"position" = "ASC"})
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
