<?php

namespace ExternalBundle\Domain\Synchronizer;

use ExternalBundle\Domain\Import\Common\ImporterFactory;
use ExternalBundle\Domain\Import\Group\ImporterFactory as GroupImporterFactory;
use ExternalBundle\Domain\Import\Larp\ImporterFactory as LarpImporterFactory;

class GroupSynchronizer extends Synchronizer
{
    protected $larpImporterFactory;

    public function __construct(
        GroupImporterFactory $importerFactory,
        LarpImporterFactory $larpImporterFactory
    ) {
        parent::__construct($importerFactory);
        $this->larpImporterFactory = $larpImporterFactory;
    }


    protected function prepare($options)
    {
        $importOptions = [];
        if (isset($options['larp_id']) && $options['larp_id']) {
            $importOptions['ids'] = [$options['larp_id']];
        }
        $this->larpImporterFactory->create($importOptions)->process();
    }
}
