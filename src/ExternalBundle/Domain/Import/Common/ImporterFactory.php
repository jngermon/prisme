<?php

namespace ExternalBundle\Domain\Import\Common;

use Ddeboer\DataImport\Step\MappingStep;
use Ddeboer\DataImport\Step\ValidatorStep;
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
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class ImporterFactory
{
    protected $connection;

    protected $writer;

    protected $validator;

    public function __construct(
        Connection $connection,
        Writer $writer
    ) {
        $this->connection = $connection;
        $this->writer = $writer;
    }

    public function getImportedClassName()
    {
        return $this->writer->getEntityName();
    }

    public function setValidator(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function create($options)
    {
        $syncUuid = Uuid::uuid4();

        $options = $this->createOptionsResolver()->resolve($options);

        $queryBuilder = $this->createQueryBuilder($options);
        $countQueryBuilderModifier = $this->createCountQueryBuilder($queryBuilder);

        $adapter = new DoctrineDbalAdapter($queryBuilder, $countQueryBuilderModifier);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage($options['batch_size']);
        $reader = new PagerfantaReader($pagerfanta);

        $workflow = new StepAggregator($reader);
        $workflow
            ->addWriter(new BatchWriter($this->writer, $options['batch_size']))
            ->setSkipItemOnFailure(false)
        ;

        $mappingStep = new MappingStep();
        foreach ($this->getMappings() as $from => $to) {
            $mappingStep->map('['.$from.']', '['.$to.']');
        }
        $workflow->addStep($mappingStep, 100);

        $workflow->addStep(new CleanStep($this->getMappings()), 99);

        if ($this->validator) {
            $validatorStep = new ValidatorStep($this->validator);
            $validatorStep->add('externalId', new \Symfony\Component\Validator\Constraints\NotNull());
            $this->configreValidatorStep($validatorStep);
            $workflow->addStep($validatorStep, 0);
        }

        $this->configureWorkflow($workflow);

        if ($options['output']) {
            $workflow->addWriter(new ConsoleProgressWriter($options['output'], $reader, 'debug', 100));
        }

        unset($options['batch_size']);
        unset($options['output']);

        $this->writer->initProcessing($syncUuid, $options);

        return new Importer($workflow);
    }

    abstract public function getMappings();

    abstract protected function createQueryBuilder($options);

    abstract protected function createCountQueryBuilder(QueryBuilder $queryBuilder);

    protected function configureWorkflow(Workflow $workflow)
    {

    }

    protected function configreValidatorStep(ValidatorStep $validatorStep)
    {

    }

    protected function createOptionsResolver()
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            'batch_size' => 100,
            'ids' => null,
            'output' => null,
        ]);

        $resolver->setNormalizer('ids', function (Options $options, $value) {
            if ($value === null) {
                return $value;
            }

            if (is_string($value)) {
                $value = intval($value);
            }

            if (!is_array($value)) {
                $value = [$value];
            }

            return $value;
        });

        $resolver->setAllowedTypes('batch_size', 'integer');
        $resolver->setAllowedTypes('ids', ['array', 'null', 'string', 'integer', 'string']);
        $resolver->setAllowedTypes('output', [OutputInterface::class, 'null']);

        return $resolver;
    }
}