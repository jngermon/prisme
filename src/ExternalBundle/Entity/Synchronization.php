<?php

namespace ExternalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Greg0ire\Enum\Bridge\Symfony\Validator\Constraint\Enum as EnumAssert;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="ExternalBundle\Entity\SynchronizationRepository")
 * @ORM\Table(name="external_synchronization")
 */
class Synchronization
{
    use TimestampableEntity;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="options", type="text")
     * @Assert\NotBlank()
     */
    protected $options;

    /**
     * @ORM\Column(type="string", length=10)
     * @EnumAssert("ExternalBundle\Entity\Enum\SyncrhonizationStatus")
     */
    protected $status;

    /**
     * @ORM\Column(type="string", length=10, nullable = true)
     */
    protected $errors;

    /**
     * @ORM\Column(type="integer")
     */
    protected $total;

    /**
     * @ORM\Column(type="integer")
     */
    protected $progress;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Assert\DateTime()
     */
    protected $startedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Assert\DateTime()
     */
    protected $endedAt;


    public function __construct()
    {
        $this->options = '{}';
        $this->status = Enum\SyncrhonizationStatus::PENDING;
        $this->total = 0;
        $this->progress = 0;
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
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param string $options
     */
    public function setOptions($options)
    {
        $this->options = $options;

        return $this;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return string
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param string $errors
     */
    public function setErrors($errors)
    {
        $this->errors = $errors;

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
