<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use ExternalBundle\Domain\Import\Common\SynchronizableInterface;
use ExternalBundle\Domain\Import\Common\SynchronizableTrait;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="organizer")
 */
class Organizer implements SynchronizableInterface
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
     * @ORM\ManyToOne(targetEntity="Larp", inversedBy="organizers")
     */
    protected $larp;

    /**
     * @ORM\ManyToOne(targetEntity="Person", inversedBy="organizers")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $person;

    /**
     * @ORM\OneToMany(targetEntity="CharacterOrganizer", mappedBy="organizer")
     */
    protected $characterOrganizers;

    public function __construct()
    {
        $this->characterOrganizers = new ArrayCollection();
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
}
