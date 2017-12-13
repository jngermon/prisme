<?php

namespace ExternalBundle\Domain\Import\Common;

use ExternalBundle\Entity\ImportationProgress;

class Importer
{
    protected $workflow;

    protected $progress;

    protected $isInit = false;

    public function __construct(
        Workflow $workflow
    ) {
        $this->workflow = $workflow;

        $this->progress = new ImportationProgress();
    }

    public function init()
    {
        if ($this->isInit) {
            return ;
        }
        $this->isInit = true;
        return $this->workflow->init($this->progress);
    }

    public function process()
    {
        $this->init();

        $res = $this->workflow->process();

        $this->workflow->finalize($res);

        return $res;
    }
}
