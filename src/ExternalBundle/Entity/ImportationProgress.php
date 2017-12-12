<?php

namespace ExternalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

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

    public function __construct()
    {
        $this->total = 0;
        $this->progress = 0;
        $this->progressing = false;
    }

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
}
