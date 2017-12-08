<?php

namespace ExternalBundle\Command;

use ExternalBundle\Domain\Import\Person\ImporterFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class ImportPersonsFromExternalCommand extends Command
{
    protected $importerFactory;

    public function __construct(
        ImporterFactory $importerFactory
    ) {
        parent::__construct();

        $this->importerFactory = $importerFactory;
    }

    protected function configure()
    {
        $this
            ->setName('external:import:persons')
            ->setDescription('Import Persons from external.')
            ->addArgument('personId', InputArgument::OPTIONAL, 'Person ID')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $ids = $input->getArgument('personId') ? [$input->getArgument('personId')] : null;

        $importer = $this->importerFactory->create(['ids' => $ids, 'output' => $output]);

        $importer->process();
    }
}
