<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use ExternalBundle\Annotations\External;
use ExternalBundle\Domain\Import\Common\SynchronizableInterface;
use ExternalBundle\Domain\Import\Common\SynchronizableTrait;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="skill")
 */
class Skill implements SynchronizableInterface, LarpRelatedInterface
{
    use TimestampableEntity;
    use SynchronizableTrait;

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
     * @ORM\OneToMany(targetEntity="CharacterSkill", mappedBy="skill")
     */
    protected $skillCharacters;

    /**
     * @ORM\Column(type="string")
     * @External()
     */
    protected $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @External()
     */
    protected $summary;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @External()
     */
    protected $description;

    public function __construct()
    {
        $this->skillCharacters = new ArrayCollection();
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
     * @return ArrayCollection
     */
    public function getSkillCharacters()
    {
        return $this->skillCharacters;
    }

    /**
     * @param ArrayCollection $skillCharacters
     */
    public function setSkillCharacters($skillCharacters)
    {
        $this->skillCharacters = $skillCharacters;

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
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * @param string $summary
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }
}
