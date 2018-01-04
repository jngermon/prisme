<?php

namespace ExternalBundle\Domain\Import\CharacterSkill;

use Ddeboer\DataImport\Step\ValidatorStep;
use Ddeboer\DataImport\Step\ValueConverterStep;
use Ddeboer\DataImport\ValueConverter\DateTimeValueConverter;
use Ddeboer\DataImport\Workflow;
use Doctrine\DBAL\Query\QueryBuilder;
use ExternalBundle\Domain\Import\Common\ImporterFactory as BaseImporterFactory;
use ExternalBundle\Domain\Import\Skill\SkillConverter;
use ExternalBundle\Domain\Import\Character\CharacterConverter;

class ImporterFactory extends BaseImporterFactory
{
    protected $skillConverter;

    protected $characterConverter;

    public function setSkillConverter(SkillConverter $skillConverter)
    {
        $this->skillConverter = $skillConverter;
    }

    public function setCharacterConverter(CharacterConverter $characterConverter)
    {
        $this->characterConverter = $characterConverter;
    }

    public function getMappings()
    {
        return [
            'idl' => 'externalId',
            'idc' => 'skill',
            'idp' => 'character',
        ];
    }

    protected function createQueryBuilder($options)
    {
        $queryBuilder = new QueryBuilder($this->connection);
        $queryBuilder
            ->select('*')
            ->from('liaison_comp_pj', 'l')
            ->innerJoin('l', 'persos', 'p', 'l.idu = p.idu AND l.idgn = p.idgn')
            ->innerJoin('l', 'comp_pj', 'c', 'l.idc = c.idc')
            ;

        if ($options['ids']) {
            $queryBuilder->andWhere($queryBuilder->expr()->in('idl', $options['ids']));
        }

        if ($options['character_id']) {
            $queryBuilder->andWhere('p.idp = :idp')
                ->setParameter('idp', $options['character_id']);
        }

        if ($options['skill_id']) {
            $queryBuilder->andWhere('idc = :idc')
                ->setParameter('idc', $options['skill_id']);
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
            $queryBuilder->select('COUNT(DISTINCT idl) AS total')
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
        if ($this->skillConverter) {
            $step->add('[skill]', $this->skillConverter);
        }

        $workflow->addStep($step, 50);
    }

    protected function configreValidatorStep(ValidatorStep $validatorStep)
    {
        $validatorStep->add('character', new \Symfony\Component\Validator\Constraints\NotNull());
        $validatorStep->add('skill', new \Symfony\Component\Validator\Constraints\NotNull());
    }

    protected function createOptionsResolver()
    {
        $resolver = parent::createOptionsResolver();

        $resolver->setDefaults([
            'character_id' => null,
            'skill_id' => null,
            'larp_id' => null,
        ]);

        $resolver->setAllowedTypes('character_id', ['null', 'integer', 'string']);
        $resolver->setAllowedTypes('skill_id', ['null', 'integer', 'string']);
        $resolver->setAllowedTypes('larp_id', ['null', 'integer', 'string']);

        return $resolver;
    }
}
