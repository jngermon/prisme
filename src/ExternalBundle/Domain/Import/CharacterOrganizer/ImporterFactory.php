<?php

namespace ExternalBundle\Domain\Import\CharacterOrganizer;

use Ddeboer\DataImport\Step\ValidatorStep;
use Ddeboer\DataImport\Step\ValueConverterStep;
use Ddeboer\DataImport\ValueConverter\DateTimeValueConverter;
use Ddeboer\DataImport\Workflow;
use Doctrine\DBAL\Query\QueryBuilder;
use ExternalBundle\Domain\Import\Common\ImporterFactory as BaseImporterFactory;
use ExternalBundle\Domain\Import\Organizer\OrganizerConverter;
use ExternalBundle\Domain\Import\Character\CharacterConverter;

class ImporterFactory extends BaseImporterFactory
{
    protected $organizerConverter;

    protected $characterConverter;

    public function setOrganizerConverter(OrganizerConverter $organizerConverter)
    {
        $this->organizerConverter = $organizerConverter;
    }

    public function setCharacterConverter(CharacterConverter $characterConverter)
    {
        $this->characterConverter = $characterConverter;
    }

    public function getMappings()
    {
        return [
            'idlgp' => 'externalId',
            'ida' => 'organizer',
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
            ->innerJoin('lgu', 'groupes', 'g', 'lgu.idg = g.idg')
            ->innerJoin('g', 'orgas', 'o', 'g.orga_ref = o.ida')
            ;

        if ($options['ids']) {
            $queryBuilder->andWhere($queryBuilder->expr()->in('idlgp', $options['ids']));
        }

        if ($options['character_id']) {
            $queryBuilder->andWhere('p.idp = :idp')
                ->setParameter('idp', $options['character_id']);
        }

        if ($options['organizer_id']) {
            $queryBuilder->andWhere('ida = :ida')
                ->setParameter('ida', $options['organizer_id']);
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
        if ($this->organizerConverter) {
            $step->add('[organizer]', $this->organizerConverter);
        }

        $workflow->addStep($step, 50);
    }

    protected function configreValidatorStep(ValidatorStep $validatorStep)
    {
        $validatorStep->add('character', new \Symfony\Component\Validator\Constraints\NotNull());
        $validatorStep->add('organizer', new \Symfony\Component\Validator\Constraints\NotNull());
    }

    protected function createOptionsResolver()
    {
        $resolver = parent::createOptionsResolver();

        $resolver->setDefaults([
            'character_id' => null,
            'organizer_id' => null,
            'larp_id' => null,
        ]);

        $resolver->setAllowedTypes('character_id', ['null', 'integer', 'string']);
        $resolver->setAllowedTypes('organizer_id', ['null', 'integer', 'string']);
        $resolver->setAllowedTypes('larp_id', ['null', 'integer', 'string']);

        return $resolver;
    }
}
