<?php

namespace ExternalBundle\Command;

use ExternalBundle\Domain\Synchronizer\Orchestrator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SynchronizeCommand extends Command
{
    public function __construct(
        Orchestrator $orchestrator
    ) {
        parent::__construct();

        $this->orchestrator = $orchestrator;
    }

    protected function configure()
    {
        $this
            ->setName('external:synchronize')
            ->setDescription('Run synchronize request form external.')
            ->addOption(
                'continue',
                null,
                InputOption::VALUE_NONE,
                'Run every pending requests ?'
            )
            ->addOption(
                'uuid',
                null,
                InputOption::VALUE_OPTIONAL,
                'Do nothing, just to identify proc'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->orchestrator->run($output, $input->getOption('continue'));
    }
}
