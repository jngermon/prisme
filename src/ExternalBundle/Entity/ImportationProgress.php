<?php

namespace ExternalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity()
 * @ORM\Table(name="external_importation_progress")
 */
class ImportationProgress
{
    use TimestampableEntity;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Synchronization", inversedBy="importations")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $synchronization;

    /**
     * @ORM\Column(type="string", length=36, nullable=true)
     */
    protected $uuid;

    /**
     * @ORM\Column(type="integer")
     */
    protected $total;

    /**
     * @ORM\Column(type="integer")
     */
    protected $progress;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $progressing;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $exceptions;

    public function __construct()
    {
        $this->total = 0;
        $this->progress = 0;
        $this->progressing = false;
        $this->uuid = Uuid::uuid4();
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Synchronization
     */
    public function getSynchronization()
    {
        return $this->synchronization;
    }

    /**
     * @param Synchronization $synchronization
     */
    public function setSynchronization($synchronization)
    {
        $this->synchronization = $synchronization;

        return $this;
    }

    /**
     * @return string
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * @param string $uuid
     */
    public function setUuid($uuid)
    {
        $this->uuid = $uuid;

        return $this;
    }

    /**
     * @return integer
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @param integer $total
     */
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * @return integer
     */
    public function getProgress()
    {
        return $this->progress;
    }

    /**
     * @param integer $progress
     */
    public function setProgress($progress)
    {
        $this->progress = $progress;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getProgressing()
    {
        return $this->progressing;
    }

    /**
     * @param boolean $progressing
     */
    public function setProgressing($progressing)
    {
        $this->progressing = $progressing;

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
     * @return stinrg
     */
    public function getExceptions()
    {
        return $this->exceptions;
    }

    /**
     * @param stinrg $exceptions
     */
    public function setExceptions($exceptions)
    {
        $this->exceptions = $exceptions;

        return $this;
    }
}