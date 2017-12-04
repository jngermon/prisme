<?php

namespace ExternalBundle\Domain\Import\Larp;

use Ddeboer\DataImport\Step\MappingStep;
use Ddeboer\DataImport\Step\ValueConverterStep;
use Ddeboer\DataImport\ValueConverter\DateTimeValueConverter;
use Ddeboer\DataImport\Workflow;
use Doctrine\DBAL\Query\QueryBuilder;
use ExternalBundle\Domain\Import\Common\Importer as BaseImporter;

class Importer extends BaseImporter
{
    protected function createQueryBuilder($larpId = null, $ids = [])
    {
        $queryBuilder = new QueryBuilder($this->connection);
        $queryBuilder
            ->select('*')
            ->from('gn')
            ;

        if ($larpId) {
            $queryBuilder->andWhere('idgn = :id')
                ->setParameter('id', $larpId);
        }

        if (count($ids)) {
            $queryBuilder->andWhere($queryBuilder->expr()->in('idgn', ':ids'))
                ->setParameter('ids', $ids);
        }

        return $queryBuilder;
    }

    protected function createCountQueryBuilder(QueryBuilder $queryBuilder)
    {
        return function ($queryBuilder) {
            $queryBuilder->select('COUNT(DISTINCT idgn) AS total')
                ->setMaxResults(1)
                ;
        };
    }

    protected function configureWorkflow(Workflow $workflow)
    {
        $workflow->addStep(new MappingStep([
            '[idgn]' => '[externalId]',
            '[nom]' => '[name]',
            '[date_deb]' => '[startedAt]',
            '[date_fin]' => '[endedAt]',
        ]));

        $step = new ValueConverterStep();
        $step->add('[startedAt]', new DateTimeValueConverter());
        $step->add('[endedAt]', new DateTimeValueConverter());

        $workflow->addStep($step);
    }
}
