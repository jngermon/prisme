<?php

namespace ExternalBundle\Entity\Enum;

use Greg0ire\Enum\AbstractEnum;

final class SynchronizationStatus extends AbstractEnum
{
    const PENDING = 'pending';
    const PROCESSING = 'processing';
    const SUCCESSED = 'successed';
    const ERROR = 'error';
}
