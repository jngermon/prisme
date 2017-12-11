<?php

namespace ExternalBundle\Domain\Import\Group;

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

    public function setLarpConverter(EntityConverter $larpConverter)
    {
        $this->larpConverter = $larpConverter;
    }

    public function getMappings()
    {
        return [
            'idg' => 'externalId',
            'idgn' => 'larp',
            'nom' => 'name',
        ];
    }

    protected function createQueryBuilder($options)
    {
        $queryBuilder = new QueryBuilder($this->connection);
        $queryBuilder
            ->select('*')
            ->from('groupes')
            ;

        if ($options['ids']) {
            $queryBuilder->andWhere($queryBuilder->expr()->in('idg', $options['ids']));
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
            $queryBuilder->select('COUNT(DISTINCT idg) AS total')
                ->setMaxResults(1)
                ;
        };
    }

    protected function configureWorkflow(Workflow $workflow)
    {
        $step = new ValueConverterStep();

        if ($this->larpConverter) {
            $step->add('[larp]', $this->larpConverter);
        }

        $workflow->addStep($step, 50);
    }

    protected function configreValidatorStep(ValidatorStep $validatorStep)
    {
        $validatorStep->add('larp', new \Symfony\Component\Validator\Constraints\NotNull());
        $validatorStep->add('name', new \Symfony\Component\Validator\Constraints\Optional());
    }

    protected function createOptionsResolver()
    {
        $resolver = parent::createOptionsResolver();

        $resolver->setDefaults([
            'larp_id' => null,
        ]);

        $resolver->setAllowedTypes('larp_id', ['null', 'integer', 'string']);

        return $resolver;
    }
}
