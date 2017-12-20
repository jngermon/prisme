<?php

namespace AppBundle\Entity;

use AppBundle\Security\ProfilableInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use ExternalBundle\Annotations\External;
use ExternalBundle\Domain\Import\Common\SynchronizableInterface;
use ExternalBundle\Domain\Import\Common\SynchronizableTrait;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="player", uniqueConstraints={
 *      @ORM\UniqueConstraint(name="player_larp_person_idx", columns={"larp_id", "person_id"})})
 * @UniqueEntity({"larp", "person"}, message="player_already_in_larp")
 */
class Player implements ProfilableInterface, SynchronizableInterface, LarpRelatedInterface
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
     * @ORM\ManyToOne(targetEntity="Larp", inversedBy="players")
     */
    protected $larp;

    /**
     * @ORM\ManyToOne(targetEntity="Person", inversedBy="players", fetch="EAGER")
     * @ORM\JoinColumn(nullable=false)
     * @External()
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

    public function __toString()
    {
        return $this->getPerson() ? $this->getPerson()->__toString() : '-';
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
            $this->characters->add($character);
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
        }
        return $this;
    }
}
