<?php

namespace ExternalBundle\Domain\Import\Common;

use Ddeboer\DataImport\Workflow;
use Ddeboer\DataImport\Workflow\StepAggregator;
use Ddeboer\DataImport\Writer;
use Ddeboer\DataImport\Writer\BatchWriter;
use Ddeboer\DataImport\Writer\ConsoleProgressWriter;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Pagerfanta\Adapter\DoctrineDbalAdapter;
use Pagerfanta\Pagerfanta;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class Importer
{
    protected $connection;

    protected $writer;

    public function __construct(
        Connection $connection,
        Writer $writer
    ) {
        $this->connection = $connection;
        $this->writer = $writer;
    }

    public function import($options)
    {
        $syncUuid = Uuid::uuid4();

        $options = $this->createOptionsResolver()->resolve($options);

        if (empty($options['larp_id']) && empty($options['ids'])) {
            throw new \RuntimeException('You must set at least one option between larp_id and ids');
        }

        $queryBuilder = $this->createQueryBuilder($options['larp_id'], $options['ids']);
        $countQueryBuilderModifier = $this->createCountQueryBuilder($queryBuilder);

        $adapter = new DoctrineDbalAdapter($queryBuilder, $countQueryBuilderModifier);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage($options['batch_size']);
        $reader = new PagerfantaReader($pagerfanta);

        if ($options['larp_id']) {
            $this->writer->setLarpIdProcessing($options['larp_id']);
        }

        if ($options['ids']) {
            $this->writer->setIdsProcessing($options['ids']);
        }

        $this->writer->setSyncUuid($syncUuid);

        $workflow = new StepAggregator($reader);
        $workflow
            ->addWriter(new BatchWriter($this->writer, $options['batch_size']))
            ->setSkipItemOnFailure(false)
        ;

        $this->configureWorkflow($workflow);

        if ($options['output']) {
            $workflow->addWriter(new ConsoleProgressWriter($options['output'], $reader, 'debug', 100));
        }

        return $workflow->process();
    }

    abstract protected function createQueryBuilder($larpId = null, $ids = []);

    abstract protected function createCountQueryBuilder(QueryBuilder $queryBuilder);

    protected function configureWorkflow(Workflow $workflow)
    {

    }

    protected function createOptionsResolver()
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            'batch_size' => 100,
            'larp_id' => null,
            'larp_external_id' => null,
            'ids' => null,
            'output' => null,
        ]);

        $resolver->setNormalizer('larp_id', function (Options $options, $value) {
            return intval($value);
        });

        $resolver->setAllowedTypes('batch_size', 'integer');
        $resolver->setAllowedTypes('larp_id', ['integer', 'null' ,'string']);
        $resolver->setAllowedTypes('ids', ['array', 'null']);
        $resolver->setAllowedTypes('output', [OutputInterface::class, 'null']);

        return $resolver;
    }
}
