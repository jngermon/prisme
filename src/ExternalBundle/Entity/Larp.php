<?php

namespace ExternalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="gn")
 */
class Larp
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", name="idgn")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=1000, name="nom")
     */
    protected $name;

    /**
     * @ORM\Column(type="datetime", name="date_deb")
     */
    protected $startedAt;

    /**
     * @ORM\Column(type="datetime", name="date_fin")
     */
    protected $endedAt;

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
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
     * @return \Datetime
     */
    public function getStartedAt()
    {
        return $this->startedAt;
    }

    /**
     * @param \Datetime $startedAt
     */
    public function setStartedAt($startedAt)
    {
        $this->startedAt = $startedAt;

        return $this;
    }

    /**
     * @return \Datetime
     */
    public function getEndedAt()
    {
        return $this->endedAt;
    }

    /**
     * @param \Datetime $endedAt
     */
    public function setEndedAt($endedAt)
    {
        $this->endedAt = $endedAt;

        return $this;
    }
}
