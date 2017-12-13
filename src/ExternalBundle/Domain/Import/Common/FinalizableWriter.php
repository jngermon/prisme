<?php

namespace ExternalBundle\Domain\Import\Common;

use Ddeboer\DataImport\Result;

interface FinalizableWriter
{
    public function finalize(Result $result);
}
