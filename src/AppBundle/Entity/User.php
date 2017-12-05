<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use MMC\FosUserBundle\Entity\User as BaseUser;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    use TimestampableEntity;

    /**
     * @ORM\OneToOne(targetEntity="Person", mappedBy="user")
     */
    protected $person;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $externalId;

    public function __toString()
    {
        return $this->getUsername() ?: '';
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
     * @return integer
     */
    public function getExternalId()
    {
        return $this->externalId;
    }

    /**
     * @param integer $externalId
     */
    public function setExternalId($externalId)
    {
        $this->externalId = $externalId;

        return $this;
    }
}
