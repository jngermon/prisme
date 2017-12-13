<?php

namespace ExternalBundle\Domain\Import\Common;

use Ddeboer\DataImport\Result;
use Ddeboer\DataImport\Workflow\StepAggregator;
use Ddeboer\DataImport\Writer;
use ExternalBundle\Entity\ImportationProgress;

class Workflow extends StepAggregator
{
    protected $initiableWriters = [];

    protected $finalizableWriters = [];

    public function addWriter(Writer $writer)
    {
        parent::addWriter($writer);

        if ($writer instanceof InitiableWriter) {
            array_push($this->initiableWriters, $writer);
        }

        if ($writer instanceof FinalizableWriter) {
            array_push($this->finalizableWriters, $writer);
        }

        return $this;
    }

    public function init(ImportationProgress $progress)
    {
        foreach ($this->initiableWriters as $writer) {
            $writer->init($progress);
        }
    }

    public function finalize(Result $result)
    {
        foreach ($this->finalizableWriters as $writer) {
            $writer->finalize($result);
        }
    }
}
