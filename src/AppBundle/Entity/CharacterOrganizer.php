<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="characterOrganizer")
 */
class CharacterOrganizer
{
    use TimestampableEntity;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Character", inversedBy="characterOrganizers")
     */
    protected $character;

    /**
     * @ORM\ManyToOne(targetEntity="Organizer", inversedBy="characterOrganizers")
     */
    protected $organizer;

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
        $this->character = $character;

        return $this;
    }

    /**
     * @return Organizer
     */
    public function getOrganizer()
    {
        return $this->organizer;
    }

    /**
     * @param Organizer $organizer
     */
    public function setOrganizer($organizer)
    {
        $this->organizer = $organizer;

        return $this;
    }
}
