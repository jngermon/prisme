<?php

namespace ExternalBundle\Domain\Import\Common;

use Ddeboer\DataImport\Writer;
use Ddeboer\DataImport\Writer\BatchWriter as BaseBatchWriter;
use ExternalBundle\Entity\ImportationProgress;

class BatchWriter extends BaseBatchWriter implements InitiableWriter
{
    protected $delegate;

    public function __construct(Writer $delegate, $size = 20)
    {
        parent::__construct($delegate, $size);

        $this->delegate = $delegate;
    }

    public function init(ImportationProgress $proress)
    {
        $this->delegate->init($proress);
    }
}
