<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use ExternalBundle\Annotations\External;
use ExternalBundle\Domain\Import\Common\SynchronizableInterface;
use ExternalBundle\Domain\Import\Common\SynchronizableTrait;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="character")
 */
class Character implements SynchronizableInterface, LarpRelatedInterface
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
     * @ORM\ManyToOne(targetEntity="Player", inversedBy="characters", fetch="EAGER")
     * @ORM\JoinColumn(nullable=true)
     * @External()
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
     * @ORM\OneToMany(targetEntity="CharacterSkill", mappedBy="character")
     */
    protected $characterSkills;

    /**
     * @ORM\Column(type="string")
     * @External()
     */
    protected $name;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @External()
     */
    protected $title;

    public function __construct()
    {
        $this->characterOrganizers = new ArrayCollection();
        $this->characterGroups = new ArrayCollection();
        $this->characterSkills = new ArrayCollection();
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

    public function countCharacterGroupsByType($type)
    {
        $nb = 0;

        foreach ($this->getCharacterGroups() as $characterGroup) {
            if ($characterGroup->getGroup()->getType() == $type) {
                $nb++;
            }
        }

        return $nb;
    }

    /**
     * @return ArrayCollection
     */
    public function getCharacterSkills()
    {
        return $this->characterSkills;
    }

    /**
     * @param CharacterSkill $characterSkill
     */
    public function addCharacterSkill(CharacterSkill $characterSkill)
    {
        if (!$this->characterSkills->contains($characterSkill)) {
            $this->characterSkills->add($characterSkill);
        }
        return $this;
    }

    /**
     * @param CharacterSkill $characterSkill
     */
    public function removeCharacterSkill(CharacterSkill $characterSkill)
    {
        if ($this->characterSkills->contains($characterSkill)) {
            $this->characterSkills->remove($characterSkill);
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

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }
}
