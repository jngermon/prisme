<?php

namespace ExternalBundle\Domain\Import\Common;

use Ddeboer\DataImport\Workflow\StepAggregator;
use Ddeboer\DataImport\Writer;

class Workflow extends StepAggregator
{
    protected $initiableWriters = [];

    public function addWriter(Writer $writer)
    {
        parent::addWriter($writer);

        if ($writer instanceof InitiableWriter) {
            array_push($this->initiableWriters, $writer);
        }

        return $this;
    }

    public function init()
    {
        foreach ($this->initiableWriters as $writer) {
            $writer->init();
        }
    }
}
