<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="characterGroup")
 */
class CharacterGroup
{
    use TimestampableEntity;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Character", inversedBy="characterGroups")
     */
    protected $character;

    /**
     * @ORM\ManyToOne(targetEntity="Group", inversedBy="characterGroups")
     */
    protected $group;

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Character
     */
    public function getCharacter()
    {
        return $this->character;
    }

    /**
     * @param Character $character
     */
    public function setCharacter($character)
    {
        if ($this->character) {
            $this->character->remove($this);
        }
        $this->character = $character;
        $character->addCharacterGroup($this);

        return $this;
    }

    /**
     * @return Group
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @param Group $group
     */
    public function setGroup($group)
    {
        if ($this->group) {
            $this->group->remove($this);
        }
        $this->group = $group;
        $group->addCharacterGroup($this);

        return $this;
    }
}
