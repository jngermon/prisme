<?php

namespace ExternalBundle\Domain\Import\Player;

use AppBundle\Entity\Player;
use ExternalBundle\Domain\Import\Common\Writer as BaseWriter;
use Doctrine\ORM\EntityManager;

class Writer extends BaseWriter
{
    protected $em;
    public function __construct(
        EntityManager $em
    ) {
        parent::__construct($em, Player::class);
    }

    protected function createOptionsResolver()
    {
        $resolver = parent::createOptionsResolver();

        $resolver->setDefaults([
            'person_id' => null,
            'larp_id' => null,
        ]);

        $resolver->setAllowedTypes('person_id', ['null', 'integer']);
        $resolver->setAllowedTypes('larp_id', ['null', 'integer']);

        return $resolver;
    }

    protected function createMarkEntitiesQueryBuilder()
    {
        $queryBuilder = parent::createMarkEntitiesQueryBuilder();

        if (!empty($this->optionsProcessing['person_id'])) {
            $queryBuilder
                ->andWhere('x.person = :pExternalId')
                ->setParameter('pExternalId', $this->optionsProcessing['person_id'])
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
