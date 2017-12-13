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
     * @EnumAssert("ExternalBundle\Entity\Enum\SynchronizationStatus")
     */
    protected $status;

    /**
     * @ORM\Column(type="text", nullable = true)
     */
    protected $errors;

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

    /**
     * @ORM\OneToMany(targetEntity="ImportationProgress", mappedBy="synchronization", cascade={"persist", "remove"})
     */
    protected $importations;

    public function __construct()
    {
        $this->options = '{}';
        $this->status = Enum\SynchronizationStatus::PENDING;
        $this->importations = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString()
    {
        return (string) $this->id ?: '';
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

    /**
     * @return ArrayCollection
     */
    public function getImportations()
    {
        return $this->importations;
    }

    /**
     * @param ArrayCollection $importations
     */
    public function setImportations($importations)
    {
        $this->importations = $importations;

        return $this;
    }
}
