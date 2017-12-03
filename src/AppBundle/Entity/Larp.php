<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="larp")
 */
class Larp
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="larps")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $owner;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    protected $name;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Assert\DateTime()
     */
    protected $startedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Assert\DateTime()
     * @Assert\GreaterThan(propertyPath="startedAt")
     */
    protected $endedAt;

    /**
     * @ORM\OneToMany(targetEntity="Organizer", mappedBy="larp")
     */
    protected $organizers;

    /**
     * @ORM\OneToMany(targetEntity="Player", mappedBy="larp")
     */
    protected $players;

    /**
     * @ORM\OneToMany(targetEntity="Character", mappedBy="larp")
     */
    protected $characters;

    /**
     * @ORM\OneToMany(targetEntity="Group", mappedBy="larp")
     */
    protected $groups;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $externalId;

    public function __construct()
    {
        $this->organizers = new ArrayCollection();
        $this->players = new ArrayCollection();
        $this->characters = new ArrayCollection();
        $this->groups = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getName() ?: '';
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return User
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * @param User $owner
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;

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
     * @return \Datetime
     */
    public function getStartedAt()
    {
        return $this->startedAt;
    }

    /**
     * @param \Datetime $startedAt
     */
    public function setStartedAt($startedAt)
    {
        $this->startedAt = $startedAt;

        return $this;
    }

    /**
     * @return \Datetime
     */
    public function getEndedAt()
    {
        return $this->endedAt;
    }

    /**
     * @param \Datetime $endedAt
     */
    public function setEndedAt($endedAt)
    {
        $this->endedAt = $endedAt;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getOrganizers()
    {
        return $this->organizers;
    }

    /**
     * @param Organizer $organizer
     */
    public function addOrganizer(Organizer $organizer)
    {
        if (!$this->organizers->contains($organizer)) {
            $this->organizers->push($organizer);
            $organizer->setLarp($this);
        }
        return $this;
    }

    /**
     * @param Organizer $organizer
     */
    public function removeOrganizer(Organizer $organizer)
    {
        if ($this->organizers->contains($organizer)) {
            $this->organizers->remove($organizer);
            $organizer->setLarp(null);
        }
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getPlayers()
    {
        return $this->players;
    }

    /**
     * @param Player $player
     */
    public function addPlayer(Player $player)
    {
        if (!$this->players->contains($player)) {
            $this->players->push($player);
            $player->setLarp($this);
        }
        return $this;
    }

    /**
     * @param Player $player
     */
    public function removePlayer(Player $player)
    {
        if ($this->players->contains($player)) {
            $this->players->remove($player);
            $player->setLarp(null);
        }
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

    /**
     * @return ArrayCollection
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * @param Group $group
     */
    public function addGroup(Group $group)
    {
        if (!$this->groups->contains($group)) {
            $this->groups->push($group);
            $group->setLarp($this);
        }
        return $this;
    }

    /**
     * @param Group $group
     */
    public function removeGroup(Group $group)
    {
        if ($this->groups->contains($group)) {
            $this->groups->remove($group);
            $group->setLarp(null);
        }
        return $this;
    }

    /**
     * @return integer
     */
    public function getExternalId()
    {
        return $this->externalId;
    }

    /**
     * @param integer $externalId
     */
    public function setExternalId($externalId)
    {
        $this->externalId = $externalId;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isExternal()
    {
        return $this->externalId != null;
    }
}
