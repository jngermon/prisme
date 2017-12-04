<?php

namespace ExternalBundle\Domain\Import\Common;

use Greg0ire\Enum\AbstractEnum;

final class Status extends AbstractEnum
{
    const PENDING = 'pending';
    const SYNCED = 'synced';
    const ERROR = 'error';
}
