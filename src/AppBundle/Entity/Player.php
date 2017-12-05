<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="player")
 */
class Player
{
    use TimestampableEntity;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Larp", inversedBy="players")
     */
    protected $larp;

    /**
     * @ORM\ManyToOne(targetEntity="Person", inversedBy="players")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $person;

    /**
     * @ORM\OneToMany(targetEntity="Character", mappedBy="player")
     */
    protected $characters;

    public function __construct()
    {
        $this->characters = new ArrayCollection();
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
        if ($this->larp) {
            $this->larp->remove($this);
        }
        $this->larp = $larp;
        $larp->addPlayer($this);

        return $this;
    }

    /**
     * @return Person
     */
    public function getPerson()
    {
        return $this->person;
    }

    /**
     * @param Person $person
     */
    public function setPerson($person)
    {
        $this->person = $person;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getCharacters()
    {
        return $this->characters;
    }

    /**
     * @param Character $character
     */
    public function addCharacter(Character $character)
    {
        if (!$this->characters->contains($character)) {
            $this->characters->push($character);
            $character->setLarp($this);
        }
        return $this;
    }

    /**
     * @param Character $character
     */
    public function removeCharacter(Character $character)
    {
        if ($this->characters->contains($character)) {
            $this->characters->remove($character);
            $character->setLarp(null);
        }
        return $this;
    }
}
