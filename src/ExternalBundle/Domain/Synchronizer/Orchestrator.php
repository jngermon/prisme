<?php

namespace ExternalBundle\Domain\Synchronizer;

use Doctrine\ORM\EntityManager;
use ExternalBundle\Entity\Enum\SyncrhonizationStatus;
use ExternalBundle\Entity\Synchronization;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Serializer\SerializerInterface;

class Orchestrator
{
    protected $synchronizer;

    protected $em;

    protected $serializer;

    public function __construct(
        SynchronizerInterface $synchronizer,
        EntityManager $em,
        SerializerInterface $serializer
    ) {
        $this->synchronizer = $synchronizer;
        $this->em = $em;
        $this->serializer = $serializer;
    }

    public function run(OutputInterface $output, $continue = false)
    {
        $repository = $this->em->getRepository(Synchronization::class);
        $running = $repository->countRunning();

        if ($running > 0) {
            $output->writeln('<error>There already is a running synchronization !</error>');
            return;
        }

        $synchronization = $repository->getNext();

        if (!$synchronization) {
            $output->writeln('<info>No more job.</info>');
            return;
        }

        $synchronization->setStatus(SyncrhonizationStatus::PROCESSING)
            ->setStartedAt(new \Datetime());
        $this->em->persist($synchronization);
        $this->em->flush();

        $runningId = $synchronization->getId();

        $output->writeln(sprintf('<info>Run synchronization : %d.</info>', $runningId));

        try {

            $options = $this->serializer->decode($synchronization->getOptions(), 'json');

            $res = $this->synchronizer->process($options);

            $synchronization = $repository->findOneById($runningId);

            if ($res->isSuccessed()) {
                $synchronization->setStatus(SyncrhonizationStatus::SUCCESSED);
            } else {
                $synchronization->setStatus(SyncrhonizationStatus::ERROR)
                    ->setError($res->getReasonPhrase());
            }

            $synchronization->setEndedAt(new \Datetime());

            $this->em->persist($synchronization);
            $this->em->flush();
            $output->writeln(sprintf('<info>Synchronization : %d is done.</info>', $runningId));

        } catch (\Exception $e) {
            $synchronization = $repository->findOneById($runningId);
            $synchronization->setStatus(SyncrhonizationStatus::ERROR);
            $synchronization->setError($e->getMessage());
            $this->em->persist($synchronization);
            $this->em->flush();

            $output->writeln(sprintf('<error>Error during synchronization : %d</error>', $runningId));
            $output->writeln('<error>'.$e->getMessage().'</error>');
        }

        if ($continue) {
            $this->run($output, $continue);
        }
    }
}
