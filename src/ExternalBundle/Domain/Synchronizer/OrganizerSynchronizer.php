<?php

namespace ExternalBundle\Domain\Synchronizer;

use ExternalBundle\Domain\Import\Common\ImporterFactory;
use ExternalBundle\Domain\Import\Organizer\ImporterFactory as OrganizerImporterFactory;
use ExternalBundle\Domain\Import\Larp\ImporterFactory as LarpImporterFactory;
use ExternalBundle\Domain\Import\Person\ImporterFactory as PersonImporterFactory;

class OrganizerSynchronizer extends Synchronizer
{
    protected $larpImporterFactory;

    protected $personImporterFactory;

    public function __construct(
        OrganizerImporterFactory $importerFactory,
        LarpImporterFactory $larpImporterFactory,
        PersonImporterFactory $personImporterFactory
    ) {
        parent::__construct($importerFactory);
        $this->larpImporterFactory = $larpImporterFactory;
        $this->personImporterFactory = $personImporterFactory;
    }


    protected function prepare($options)
    {
        $importOptions = [];
        if (isset($options['larp_id']) && $options['larp_id']) {
            $importOptions['ids'] = [$options['larp_id']];
        }
        $this->larpImporterFactory->create($importOptions)->process();

        $importOptions = [];
        if (isset($options['person_id']) && $options['person_id']) {
            $importOptions['ids'] = [$options['person_id']];
        }
        $this->personImporterFactory->create($importOptions)->process();
    }
}
