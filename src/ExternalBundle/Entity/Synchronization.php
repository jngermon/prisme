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
     * @ORM\OrderBy({"id" = "asc"})
     */
    protected $importations;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $pid;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $command;

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

    /**
     * @return integer
     */
    public function getTotal()
    {
        $total = 0;

        foreach ($this->getImportations() as $importation) {
            $total += $importation->getTotal();
        }

        return $total;
    }

    /**
     * @return integer
     */
    public function getProgress()
    {
        $progress = 0;

        foreach ($this->getImportations() as $importation) {
            $progress += $importation->getProgress();
        }

        return $progress;
    }

    /**
     * @return boolean
     */
    public function getProgressing()
    {
        foreach ($this->getImportations() as $importation) {
            if ($importation->getProgressing()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return ImportationProgress
     */
    public function getProgressingImportation()
    {
        foreach ($this->getImportations() as $importation) {
            if ($importation->getProgressing()) {
                return $importation;
            }
        }

        return null;
    }

    /**
     * @return interger
     */
    public function getPid()
    {
        return $this->pid;
    }

    /**
     * @param interger $pid
     */
    public function setPid($pid)
    {
        $this->pid = $pid;

        return $this;
    }

    /**
     * @return string
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * @param string $command
     */
    public function setCommand($command)
    {
        $this->command = $command;

        return $this;
    }
}
