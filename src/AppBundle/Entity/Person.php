<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use ExternalBundle\Domain\Import\Common\SynchronizableInterface;
use ExternalBundle\Domain\Import\Common\SynchronizableTrait;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Greg0ire\Enum\Bridge\Symfony\Validator\Constraint\Enum as EnumAssert;
use ExternalBundle\Annotations\External;

/**
 * @ORM\Entity
 * @ORM\Table(name="person")
 */
class Person implements SynchronizableInterface
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
     * @ORM\OneToOne(targetEntity="User", inversedBy="person")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $user;

    /**
     * @ORM\Column(type="string")
     * @External()
     */
    protected $firstname;

    /**
     * @ORM\Column(type="string")
     * @External()
     */
    protected $lastname;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @External()
     */
    protected $phone;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @External()
     */
    protected $birthDate;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     * @EnumAssert("AppBundle\Entity\Enum\Gender")
     * @External()
     */
    protected $gender;

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
        return $this->getFirstname() . ' ' . $this->getLastname();
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
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return \Datetime
     */
    public function getBirthDate()
    {
        return $this->birthDate;
    }

    /**
     * @param \Datetime $birthDate
     */
    public function setBirthDate($birthDate)
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    /**
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param string $gender
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

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
            $this->larps->add($larp);
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
        if (!$this->getOrganizers()->contains($organizer)) {
            $this->getOrganizers()->add($organizer);
        }
        return $this;
    }

    /**
     * @param Organizer $organizer
     */
    public function removeOrganizer(Organizer $organizer)
    {
        if ($this->getOrganizers()->contains($organizer)) {
            $this->getOrganizers()->remove($organizer);
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
            $this->players->add($player);
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
        }
        return $this;
    }
}
