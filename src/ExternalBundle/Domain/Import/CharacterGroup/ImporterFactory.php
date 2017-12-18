<?php

namespace ExternalBundle\Domain\Import\CharacterGroup;

use Ddeboer\DataImport\Step\ValidatorStep;
use Ddeboer\DataImport\Step\ValueConverterStep;
use Ddeboer\DataImport\ValueConverter\DateTimeValueConverter;
use Ddeboer\DataImport\Workflow;
use Doctrine\DBAL\Query\QueryBuilder;
use ExternalBundle\Domain\Import\Common\ImporterFactory as BaseImporterFactory;
use ExternalBundle\Domain\Import\Group\GroupConverter;
use ExternalBundle\Domain\Import\Character\CharacterConverter;

class ImporterFactory extends BaseImporterFactory
{
    protected $groupConverter;

    protected $characterConverter;

    public function setGroupConverter(GroupConverter $groupConverter)
    {
        $this->groupConverter = $groupConverter;
    }

    public function setCharacterConverter(CharacterConverter $characterConverter)
    {
        $this->characterConverter = $characterConverter;
    }

    public function getMappings()
    {
        return [
            'idlgp' => 'externalId',
            'idg' => 'group',
            'idp' => 'character',
        ];
    }

    protected function createQueryBuilder($options)
    {
        $queryBuilder = new QueryBuilder($this->connection);
        $queryBuilder
            ->select('*')
            ->from('liaison_group_user', 'lgu')
            ->innerJoin('lgu', 'persos', 'p', 'lgu.idu = p.idu')
            ;

        if ($options['ids']) {
            $queryBuilder->andWhere($queryBuilder->expr()->in('idlgp', $options['ids']));
        }

        if ($options['character_id']) {
            $queryBuilder->andWhere('p.idp = :idp')
                ->setParameter('idp', $options['character_id']);
        }

        if ($options['group_id']) {
            $queryBuilder->andWhere('idg = :idg')
                ->setParameter('idg', $options['group_id']);
        }

        if ($options['larp_id']) {
            $queryBuilder->andWhere('p.idgn = :idgn')
                ->setParameter('idgn', $options['larp_id']);
        }

        return $queryBuilder;
    }

    protected function createCountQueryBuilder(QueryBuilder $queryBuilder)
    {
        return function ($queryBuilder) {
            $queryBuilder->select('COUNT(DISTINCT idlgp) AS total')
                ->setMaxResults(1)
                ;
        };
    }

    protected function configureWorkflow(Workflow $workflow)
    {
        $step = new ValueConverterStep();

        if ($this->characterConverter) {
            $step->add('[character]', $this->characterConverter);
        }
        if ($this->groupConverter) {
            $step->add('[group]', $this->groupConverter);
        }

        $workflow->addStep($step, 50);
    }

    protected function configreValidatorStep(ValidatorStep $validatorStep)
    {
        $validatorStep->add('character', new \Symfony\Component\Validator\Constraints\NotNull());
        $validatorStep->add('group', new \Symfony\Component\Validator\Constraints\NotNull());
    }

    protected function createOptionsResolver()
    {
        $resolver = parent::createOptionsResolver();

        $resolver->setDefaults([
            'character_id' => null,
            'group_id' => null,
            'larp_id' => null,
        ]);

        $resolver->setAllowedTypes('character_id', ['null', 'integer', 'string']);
        $resolver->setAllowedTypes('group_id', ['null', 'integer', 'string']);
        $resolver->setAllowedTypes('larp_id', ['null', 'integer', 'string']);

        return $resolver;
    }
}
