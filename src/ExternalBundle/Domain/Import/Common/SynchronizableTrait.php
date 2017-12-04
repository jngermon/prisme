<?php

namespace ExternalBundle\Domain\Import\Common;

use Doctrine\ORM\Mapping as ORM;

trait SynchronizableTrait
{
    /**
     * @ORM\Column(type="string", length=36, nullable = true)
     */
    protected $syncUuid;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $externalId;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Assert\DateTime()
     */
    protected $syncedAt;

    /**
     * @ORM\Column(type="string", length=10, nullable = true)
     */
    protected $syncStatus;

    /**
     * @ORM\Column(type="string", nullable = true)
     */
    protected $syncErrors;

    /**
     * @return string
     */
    public function getSyncUuid()
    {
        return $this->syncUuid;
    }

    /**
     * @param string $syncUuid
     */
    public function setSyncUuid($syncUuid)
    {
        $this->syncUuid = $syncUuid;

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

    /**
     * @return boolean
     */
    public function isExternal()
    {
        return $this->externalId != null;
    }

    /**
     * @return \Datetime
     */
    public function getSyncedAt()
    {
        return $this->syncedAt;
    }

    /**
     * @param \Datetime $syncedAt
     */
    public function setSyncedAt(\Datetime $syncedAt)
    {
        $this->syncedAt = $syncedAt;

        return $this;
    }

    /**
     * @return string
     */
    public function getSyncStatus()
    {
        return $this->syncStatus;
    }

    /**
     * @param string $syncStatus
     */
    public function setSyncStatus($syncStatus = null)
    {
        $this->syncStatus = $syncStatus;

        return $this;
    }

    /**
     * @return string
     */
    public function getSyncErrors()
    {
        return $this->syncErrors;
    }

    /**
     * @param string $syncErrors
     */
    public function setSyncErrors($syncErrors = null)
    {
        $this->syncErrors = $syncErrors;

        return $this;
    }
}
