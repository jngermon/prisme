<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="character")
 */
class Character
{
    use TimestampableEntity;

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
     * @ORM\ManyToOne(targetEntity="Player", inversedBy="characters")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $player;

    /**
     * @ORM\OneToMany(targetEntity="CharacterOrganizer", mappedBy="character")
     */
    protected $characterOrganizers;

    /**
     * @ORM\OneToMany(targetEntity="CharacterGroup", mappedBy="character")
     */
    protected $characterGroups;

    /**
     * @ORM\Column(type="string")
     */
    protected $name;

    public function __construct()
    {
        $this->characterOrganizers = new ArrayCollection();
        $this->characterGroups = new ArrayCollection();
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
     * @return Player
     */
    public function getPlayer()
    {
        return $this->player;
    }

    /**
     * @param Player $player
     */
    public function setPlayer($player)
    {
        $this->player = $player;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getCharacterOrganizers()
    {
        return $this->characterOrganizers;
    }

    /**
     * @param CharacterOrganizer $characterOrganizer
     */
    public function addCharacterOrganizer(CharacterOrganizer $characterOrganizer)
    {
        if (!$this->characterOrganizers->contains($characterOrganizer)) {
            $this->characterOrganizers->add($characterOrganizer);
        }
        return $this;
    }

    /**
     * @param CharacterOrganizer $characterOrganizer
     */
    public function removeCharacterOrganizer(CharacterOrganizer $characterOrganizer)
    {
        if ($this->characterOrganizers->contains($characterOrganizer)) {
            $this->characterOrganizers->remove($characterOrganizer);
        }
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getCharacterGroups()
    {
        return $this->characterGroups;
    }

    /**
     * @param CharacterGroup $characterGroup
     */
    public function addCharacterGroup(CharacterGroup $characterGroup)
    {
        if (!$this->characterGroups->contains($characterGroup)) {
            $this->characterGroups->add($characterGroup);
        }
        return $this;
    }

    /**
     * @param CharacterGroup $characterGroup
     */
    public function removeCharacterGroup(CharacterGroup $characterGroup)
    {
        if ($this->characterGroups->contains($characterGroup)) {
            $this->characterGroups->remove($characterGroup);
        }
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
}
