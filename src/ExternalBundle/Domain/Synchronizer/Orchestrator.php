<?php

namespace ExternalBundle\Domain\Synchronizer;

use Doctrine\ORM\EntityManager;
use ExternalBundle\Entity\Enum\SynchronizationStatus;
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
        $repository = $this->getEm()->getRepository(Synchronization::class);
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

        $synchronization->setStatus(SynchronizationStatus::PROCESSING)
            ->setStartedAt(new \Datetime());
        $synchronization->setPid(getmypid());
        $synchronization->setCommand(implode($_SERVER['argv'], ' '));
        $this->getEm()->persist($synchronization);
        $this->getEm()->flush();

        $runningId = $synchronization->getId();

        $output->writeln(sprintf('<info>Run synchronization : %d.</info>', $runningId));

        try {

            $options = $this->serializer->decode($synchronization->getOptions(), 'json');

            $options['synchronization'] = $synchronization;

            $res = $this->synchronizer->process($options);

            $synchronization = $repository->findOneById($runningId);

            if ($res->isSuccessed()) {
                $synchronization->setStatus(SynchronizationStatus::SUCCESSED);
                $output->writeln(sprintf('<info>Synchronization : %d is done.</info>', $runningId));
            } else {
                $synchronization->setStatus(SynchronizationStatus::ERROR)
                    ->setErrors($res->getReasonPhrase());

                $output->writeln(sprintf('<error>Error during synchronization : %d</error>', $runningId));
            }

            $synchronization->setEndedAt(new \Datetime());

            $this->getEm()->persist($synchronization);
            $this->getEm()->flush();


        } catch (\Exception $e) {
            $synchronization = $repository->findOneById($runningId);
            $synchronization->setStatus(SynchronizationStatus::ERROR);
            $synchronization->setErrors($e->getMessage());
            $this->getEm()->persist($synchronization);
            $this->getEm()->flush();

            $output->writeln(sprintf('<error>Error during synchronization : %d</error>', $runningId));
            $output->writeln('<error>'.$e->getMessage().'</error>');
        }

        if ($continue) {
            $this->run($output, $continue);
        }
    }

    protected function getEm()
    {
        if (!$this->em->isOpen()) {
            $this->em = $this->em->create(
                $this->em->getConnection(),
                $this->em->getConfiguration()
            );
        }

        return $this->em;
    }
}
