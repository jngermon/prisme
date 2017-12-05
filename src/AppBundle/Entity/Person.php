<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="person")
 */
class Person
{
    use TimestampableEntity;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToOne(targetEntity="User", inversedBy="person")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $user;

    /**
     * @ORM\Column(type="string")
     */
    protected $firstname;

    /**
     * @ORM\Column(type="string")
     */
    protected $lastname;

    /**
     * @ORM\OneToMany(targetEntity="Player", mappedBy="person")
     */
    protected $players;

    /**
     * @ORM\OneToMany(targetEntity="Organizer", mappedBy="person")
     */
    protected $organizers;

    /**
     * @ORM\OneToMany(targetEntity="Larp", mappedBy="owner")
     */
    protected $larps;

    public function __construct()
    {
        $this->organizers = new ArrayCollection();
        $this->players = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getFirstname() . '' . $this->getLastname();
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
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param string $firstname
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param string $lastname
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getLarps()
    {
        return $this->larps;
    }

    /**
     * @param Larp $larp
     */
    public function addLarp(Larp $larp)
    {
        if (!$this->larps->contains($larp)) {
            $this->larps->push($larp);
            $larp->setPerson($this);
        }
        return $this;
    }

    /**
     * @param Larp $larp
     */
    public function removeLarp(Larp $larp)
    {
        if ($this->larps->contains($larp)) {
            $this->larps->remove($larp);
            $larp->setPerson(null);
        }
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
            $organizer->setPerson($this);
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
            $organizer->setPerson(null);
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
            $player->setPerson($this);
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
            $player->setPerson(null);
        }
        return $this;
    }
}
