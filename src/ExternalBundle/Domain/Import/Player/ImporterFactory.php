<?php

namespace ExternalBundle\Domain\Import\Player;

use Ddeboer\DataImport\Step\ValidatorStep;
use Ddeboer\DataImport\Step\ValueConverterStep;
use Ddeboer\DataImport\ValueConverter\DateTimeValueConverter;
use Ddeboer\DataImport\Workflow;
use Doctrine\DBAL\Query\QueryBuilder;
use ExternalBundle\Domain\Import\Common\Converter\EntityConverter;
use ExternalBundle\Domain\Import\Common\ImporterFactory as BaseImporterFactory;

class ImporterFactory extends BaseImporterFactory
{
    protected $larpConverter;

    protected $personConverter;

    public function setLarpConverter(EntityConverter $larpConverter)
    {
        $this->larpConverter = $larpConverter;
    }

    public function setPersonConverter(EntityConverter $personConverter)
    {
        $this->personConverter = $personConverter;
    }

    public function getMappings()
    {
        return [
            'idi' => 'externalId',
            'idgn' => 'larp',
            'idu' => 'person',
        ];
    }

    protected function createQueryBuilder($options)
    {
        $queryBuilder = new QueryBuilder($this->connection);
        $queryBuilder
            ->select('*')
            ->from('inscriptions')
            ;

        if ($options['ids']) {
            $queryBuilder->andWhere($queryBuilder->expr()->in('idi', $options['ids']));
        }

        if ($options['person_id']) {
            $queryBuilder->andWhere('idu = :idu')
                ->setParameter('idu', $options['person_id']);
        }

        if ($options['larp_id']) {
            $queryBuilder->andWhere('idgn = :idgn')
                ->setParameter('idgn', $options['larp_id']);
        }

        return $queryBuilder;
    }

    protected function createCountQueryBuilder(QueryBuilder $queryBuilder)
    {
        return function ($queryBuilder) {
            $queryBuilder->select('COUNT(DISTINCT idi) AS total')
                ->setMaxResults(1)
                ;
        };
    }

    protected function configureWorkflow(Workflow $workflow)
    {
        $step = new ValueConverterStep();

        if ($this->personConverter) {
            $step->add('[person]', $this->personConverter);
            $step->add('[larp]', $this->larpConverter);
        }

        $workflow->addStep($step, 50);
    }

    protected function configreValidatorStep(ValidatorStep $validatorStep)
    {
        $validatorStep->add('person', new \Symfony\Component\Validator\Constraints\NotNull());
        $validatorStep->add('larp', new \Symfony\Component\Validator\Constraints\NotNull());
    }

    protected function createOptionsResolver()
    {
        $resolver = parent::createOptionsResolver();

        $resolver->setDefaults([
            'person_id' => null,
            'larp_id' => null,
        ]);

        $resolver->setAllowedTypes('person_id', ['null', 'integer', 'string']);
        $resolver->setAllowedTypes('larp_id', ['null', 'integer', 'string']);

        return $resolver;
    }
}
