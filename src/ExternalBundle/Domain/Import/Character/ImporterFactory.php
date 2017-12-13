<?php

namespace ExternalBundle\Domain\Import\Character;

use Ddeboer\DataImport\Step\ValidatorStep;
use Ddeboer\DataImport\Step\ValueConverterStep;
use Ddeboer\DataImport\ValueConverter\DateTimeValueConverter;
use Ddeboer\DataImport\Workflow;
use Doctrine\DBAL\Query\QueryBuilder;
use ExternalBundle\Domain\Import\Common\Converter\EntityConverter;
use ExternalBundle\Domain\Import\Common\ImporterFactory as BaseImporterFactory;
use ExternalBundle\Domain\Import\Player\PlayerConverter;

class ImporterFactory extends BaseImporterFactory
{
    protected $larpConverter;

    protected $playerConverter;

    public function setLarpConverter(EntityConverter $larpConverter)
    {
        $this->larpConverter = $larpConverter;
    }

    public function setPlayerConverter(PlayerConverter $playerConverter)
    {
        $this->playerConverter = $playerConverter;
    }

    public function getMappings()
    {
        return [
            'idp' => 'externalId',
            'idgn' => 'larp',
            'idu' => 'player',
            'nom' => 'name',
        ];
    }

    protected function createQueryBuilder($options)
    {
        $queryBuilder = new QueryBuilder($this->connection);
        $queryBuilder
            ->select('*')
            ->from('persos')
            ;

        if ($options['ids']) {
            $queryBuilder->andWhere($queryBuilder->expr()->in('idp', $options['ids']));
        }

        if ($options['player_id']) {
            $queryBuilder->andWhere('idu = :idu')
                ->setParameter('idu', $options['player_id']);
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
            $queryBuilder->select('COUNT(DISTINCT idp) AS total')
                ->setMaxResults(1)
                ;
        };
    }

    protected function configureWorkflow(Workflow $workflow)
    {
        $step = new ValueConverterStep();

        if ($this->playerConverter) {
            $step->add('[player]', $this->playerConverter);
        }
        if ($this->larpConverter) {
            $step->add('[larp]', $this->larpConverter);
        }

        $workflow->addStep($step, 50);
    }

    protected function configreValidatorStep(ValidatorStep $validatorStep)
    {
        $validatorStep->add('player', new \Symfony\Component\Validator\Constraints\Optional());
        $validatorStep->add('larp', new \Symfony\Component\Validator\Constraints\NotNull());
        $validatorStep->add('name', new \Symfony\Component\Validator\Constraints\Optional());
    }

    protected function createOptionsResolver()
    {
        $resolver = parent::createOptionsResolver();

        $resolver->setDefaults([
            'player_id' => null,
            'larp_id' => null,
        ]);

        $resolver->setAllowedTypes('player_id', ['null', 'integer', 'string']);
        $resolver->setAllowedTypes('larp_id', ['null', 'integer', 'string']);

        return $resolver;
    }
}
