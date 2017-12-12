<?php

namespace ExternalBundle\Domain\Import\Common;

class Importer
{
    protected $workflow;

    protected $isInit = false;

    public function __construct(
        Workflow $workflow
    ) {
        $this->workflow = $workflow;
    }

    public function init()
    {
        if ($this->isInit) {
            return ;
        }
        $this->isInit = true;
        return $this->workflow->init();
    }

    public function process()
    {
        $this->init();

        return $this->workflow->process();
    }
}
