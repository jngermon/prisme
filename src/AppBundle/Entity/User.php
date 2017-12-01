<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use MMC\FosUserBundle\Entity\User as BaseUser;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\OneToMany(targetEntity="Organizer", mappedBy="user")
     */
    protected $organizers;

    /**
     * @ORM\OneToMany(targetEntity="Player", mappedBy="user")
     */
    protected $players;

    public function __construct()
    {
        parent::__construct();

        $this->organizers = new ArrayCollection();
        $this->players = new ArrayCollection();
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
}
