<?php

namespace ExternalBundle\Domain\Import\Common;

use ExternalBundle\Entity\ImportationProgress;

interface InitiableWriter
{
    public function init(ImportationProgress $progress);
}
