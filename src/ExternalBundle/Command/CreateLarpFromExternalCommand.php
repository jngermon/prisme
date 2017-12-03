<?php

namespace ExternalBundle\Command;

use ExternalBundle\Domain\Larp\Creator as LarpCreator;
use ExternalBundle\Domain\Larp\Provider as LarpProvider;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class CreateLarpFromExternalCommand extends Command
{
    protected $provider;

    protected $creator;

    public function __construct(
        LarpProvider $provider,
        LarpCreator $creator
    ) {
        parent::__construct();

        $this->provider = $provider;
        $this->creator = $creator;
    }

    protected function configure()
    {
        $this
            ->setName('external:create-larp')
            ->setDescription('Create Larp from external.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');

        foreach ($this->provider->getAll() as $larp) {
            $output->writeln(sprintf('%d : %s (%s)', $larp->getId(), $larp->getName(), $larp->getStartedAt()->format('Y-m-d')));
        }

        $question = new Question('Please enter the ID of the Larp : ');

        $larpId = $helper->ask($input, $output, $question);

        $selectedLarp = null;
        foreach ($this->provider->getAll() as $larp) {
            if ($larp->getId() == $larpId) {
                $selectedLarp = $larp;
            }
        }

        if (!$selectedLarp) {
            $output->writeln(sprintf('<error>The id %s does not match to external Larp</error>', $larpId));
            return ;
        }

        try {
            $larp = $this->creator->createFromExternalLarp($selectedLarp);
            $output->writeln(sprintf('<info>The Larp was created with ID : %s</info>', $larp->getId()));
        } catch (\Exception $e) {
            $output->writeln(sprintf('<error>The Larp cannot be create for this reason : %s</error>', $e->getMessage()));
        }
    }
}
