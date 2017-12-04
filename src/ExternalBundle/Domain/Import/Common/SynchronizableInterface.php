<?php

namespace ExternalBundle\Domain\Import\Common;

interface SynchronizableInterface
{
    public function getSyncUuid();
    public function setSyncUuid($uuid);

    public function getExternalId();
    public function setExternalId($id);
    public function isExternal();

    public function getSyncedAt();
    public function setSyncedAt(\Datetime $date);

    public function getSyncStatus();
    public function setSyncStatus($status);

    public function getSyncErrors();
    public function setSyncErrors($errors);
}
