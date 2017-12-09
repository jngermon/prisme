<?php

namespace ExternalBundle\Domain\Synchronizer;

use ExternalBundle\Domain\Import\Common\ImporterFactory;
use ExternalBundle\Domain\Import\Character\ImporterFactory as CharacterImporterFactory;
use ExternalBundle\Domain\Import\Larp\ImporterFactory as LarpImporterFactory;
use ExternalBundle\Domain\Import\Player\ImporterFactory as PlayerImporterFactory;

class CharacterSynchronizer extends Synchronizer
{
    protected $larpImporterFactory;

    protected $playerImporterFactory;

    public function __construct(
        CharacterImporterFactory $importerFactory,
        LarpImporterFactory $larpImporterFactory,
        PlayerImporterFactory $playerImporterFactory
    ) {
        parent::__construct($importerFactory);
        $this->larpImporterFactory = $larpImporterFactory;
        $this->playerImporterFactory = $playerImporterFactory;
    }

    protected function prepare($options)
    {
        $importOptions = [];
        if (isset($options['larp_id']) && $options['larp_id']) {
            $importOptions['ids'] = [$options['larp_id']];
        }
        $this->larpImporterFactory->create($importOptions)->process();

        $importOptions = [];
        if (isset($options['player_id']) && $options['player_id']) {
            $importOptions['ids'] = [$options['player_id']];
        }
        $this->playerImporterFactory->create($importOptions)->process();
    }
}
