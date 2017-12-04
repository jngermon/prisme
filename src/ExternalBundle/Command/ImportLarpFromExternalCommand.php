<?php

namespace ExternalBundle\Command;

use ExternalBundle\Domain\Import\Larp\Importer;
use ExternalBundle\Domain\Larp\Provider as LarpProvider;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class ImportLarpFromExternalCommand extends Command
{
    protected $provider;

    protected $importer;

    public function __construct(
        LarpProvider $provider,
        Importer $importer
    ) {
        parent::__construct();

        $this->provider = $provider;
        $this->importer = $importer;
    }

    protected function configure()
    {
        $this
            ->setName('external:import-larp')
            ->setDescription('Import Larp from external.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');

        foreach ($this->provider->getAll() as $larp) {
            $output->writeln(sprintf('%d : %s (%s)', $larp['idgn'], $larp['nom'], (new \Datetime($larp['date_deb']))->format('Y-m-d')));
        }

        $question = new Question('Please enter the ID of the Larp : ');

        $larpId = $helper->ask($input, $output, $question);

        $this->importer->import(['larp_id' => $larpId, 'output' => $output]);
    }
}
