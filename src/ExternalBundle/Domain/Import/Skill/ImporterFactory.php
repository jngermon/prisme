<?php

namespace ExternalBundle\Domain\Import\Skill;

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
            'idc' => 'externalId',
            'idgn' => 'larp',
            'nom' => 'name',
            'description' => 'summary',
            'description_full' => 'description',
        ];
    }

    protected function createQueryBuilder($options)
    {
        $queryBuilder = new QueryBuilder($this->connection);
        $queryBuilder
            ->select('*')
            ->from('comp_pj')
            ;

        if ($options['ids']) {
            $queryBuilder->andWhere($queryBuilder->expr()->in('idc', $options['ids']));
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
            $queryBuilder->select('COUNT(DISTINCT idc) AS total')
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
        $validatorStep->add('summary', new \Symfony\Component\Validator\Constraints\Optional());
        $validatorStep->add('description', new \Symfony\Component\Validator\Constraints\Optional());
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
