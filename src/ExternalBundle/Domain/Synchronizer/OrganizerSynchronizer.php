<?php

namespace ExternalBundle\Domain\Synchronizer;

use ExternalBundle\Domain\Import\Common\Importer;
use ExternalBundle\Domain\Import\Organizer\Importer as OrganizerImporter;
use ExternalBundle\Domain\Import\Larp\Importer as LarpImporter;
use ExternalBundle\Domain\Import\Person\Importer as PersonImporter;

class OrganizerSynchronizer extends Synchronizer
{
    protected $larpImporter;

    protected $personImporter;

    public function __construct(
        OrganizerImporter $importer,
        LarpImporter $larpImporter,
        PersonImporter $personImporter
    ) {
        parent::__construct($importer);
        $this->larpImporter = $larpImporter;
        $this->personImporter = $personImporter;
    }


    protected function prepare($options)
    {
        $importOptions = [];
        if (isset($options['larp_id']) && $options['larp_id']) {
            $importOptions['ids'] = [$options['larp_id']];
        }
        $this->larpImporter->import($importOptions);

        $importOptions = [];
        if (isset($options['person_id']) && $options['person_id']) {
            $importOptions['ids'] = [$options['person_id']];
        }
        $this->personImporter->import($importOptions);
    }
}
