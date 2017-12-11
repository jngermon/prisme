<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use ExternalBundle\Annotations\External;
use ExternalBundle\Domain\Import\Common\SynchronizableInterface;
use ExternalBundle\Domain\Import\Common\SynchronizableTrait;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="larp_group")
 */
class Group implements LarpRelatedInterface, SynchronizableInterface
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
     * @ORM\ManyToOne(targetEntity="Larp", inversedBy="groups")
     */
    protected $larp;

    /**
     * @ORM\OneToMany(targetEntity="CharacterGroup", mappedBy="group")
     */
    protected $characterGroups;

    /**
     * @ORM\Column(type="string")
     * @External()
     */
    protected $name;

    public function __construct()
    {
        $this->characterGroups = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name ?: '';
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
