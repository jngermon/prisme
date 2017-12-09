<?php

namespace ExternalBundle\Domain\Import\Character;

use AppBundle\Entity\Character;
use ExternalBundle\Domain\Import\Common\Writer as BaseWriter;
use Doctrine\ORM\EntityManager;

class Writer extends BaseWriter
{
    protected $em;
    public function __construct(
        EntityManager $em
    ) {
        parent::__construct($em, Character::class);
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

    protected function createMarkEntitiesQueryBuilder()
    {
        $queryBuilder = parent::createMarkEntitiesQueryBuilder();

        if (!empty($this->optionsProcessing['player_id'])) {
            $queryBuilder
                ->innerJoin('x.player', 'p')
                ->andWhere('p.person = :pExternalId')
                ->setParameter('pExternalId', $this->optionsProcessing['player_id'])
                ;
        }

        if (!empty($this->optionsProcessing['larp_id'])) {
            $queryBuilder
                ->andWhere('x.larp = :lExternalId')
                ->setParameter('lExternalId', $this->optionsProcessing['larp_id'])
                ;
        }

        return $queryBuilder;
    }
}
