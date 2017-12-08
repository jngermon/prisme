<?php

namespace ExternalBundle\Domain\Import\Common;

use Ddeboer\DataImport\Workflow;

class Importer
{
    protected $workflow;

    public function __construct(
        Workflow $workflow
    ) {
        $this->workflow = $workflow;
    }

    public function process()
    {
        return $this->workflow->process();
    }
}
