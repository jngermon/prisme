<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use ExternalBundle\Annotations\External;
use ExternalBundle\Domain\Import\Common\SynchronizableInterface;
use ExternalBundle\Domain\Import\Common\SynchronizableTrait;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Greg0ire\Enum\Bridge\Symfony\Validator\Constraint\Enum as EnumAssert;
use Symfony\Component\Validator\Constraints as Assert;

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

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     * @EnumAssert("AppBundle\Entity\Enum\GroupType")
     * @Assert\Expression("!this.getLarp().isExternal() or value != 'logistics'", message="group_cant_be_logistics_with_external_larp", groups={"Normal"})
     * @External()
     */
    protected $type;

    public function __construct()
    {
        $this->characterGroups = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name ?: '-';
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

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }
}
