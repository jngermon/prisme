<?php

namespace ExternalBundle\Domain\Import\Person;

use Ddeboer\DataImport\Step\ValueConverterStep;
use Ddeboer\DataImport\Workflow;
use Doctrine\DBAL\Query\QueryBuilder;
use ExternalBundle\Domain\Import\Common\DateTimeValueConverter;
use ExternalBundle\Domain\Import\Common\Importer as BaseImporter;

class Importer extends BaseImporter
{
    public function getMappings()
    {
        return [
            'idu' => 'externalId',
            'nom' => 'lastname',
            'prenom' => 'firstname',
            'tel' => 'phone',
            'birth' => 'birthDate',
            'sexe' => 'gender',
        ];
    }

    protected function createQueryBuilder($options)
    {
        $queryBuilder = new QueryBuilder($this->connection);
        $queryBuilder
            ->select('*')
            ->from('users')
            ;

        if ($options['ids']) {
            $queryBuilder->andWhere($queryBuilder->expr()->in('idu', $options['ids']));
        }

        return $queryBuilder;
    }

    protected function createCountQueryBuilder(QueryBuilder $queryBuilder)
    {
        return function ($queryBuilder) {
            $queryBuilder->select('COUNT(DISTINCT idu) AS total')
                ->setMaxResults(1)
                ;
        };
    }

    protected function configureWorkflow(Workflow $workflow)
    {
        $step = new ValueConverterStep();
        $step->add('[birthDate]', new DateTimeValueConverter());
        $step->add('[gender]', new GenderConverter());

        $workflow->addStep($step);
    }
}
