<?php

namespace ExternalBundle\Domain\Import\CharacterGroup;

use AppBundle\Entity\CharacterGroup;
use ExternalBundle\Domain\Import\Common\Writer as BaseWriter;
use Doctrine\ORM\EntityManager;

class Writer extends BaseWriter
{
    protected $em;
    public function __construct(
        EntityManager $em
    ) {
        parent::__construct($em, CharacterGroup::class);
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

    protected function createMarkEntitiesQueryBuilder()
    {
        $queryBuilder = parent::createMarkEntitiesQueryBuilder();

        if (!empty($this->optionsProcessing['character_id'])) {
            $queryBuilder
                ->andWhere('x.character = :cExternalId')
                ->setParameter('cExternalId', $this->optionsProcessing['character_id'])
                ;
        }

        if (!empty($this->optionsProcessing['group_id'])) {
            $queryBuilder
                ->andWhere('x.group = :gExternalId')
                ->setParameter('gExternalId', $this->optionsProcessing['group_id'])
                ;
        }

        if (!empty($this->optionsProcessing['larp_id'])) {

            $subQB = $this->entityRepository->createQueryBuilder('x2')
                ->select('x2.id')
                ->innerJoin('x2.group', 'g')
                ->andWhere('g.larp = :lExternalId');


            $queryBuilder
                ->andWhere($queryBuilder->expr()->in('x.id', $subQB->getDQL()))
                ->setParameter('lExternalId', $this->optionsProcessing['larp_id'])
                ;
        }

        return $queryBuilder;
    }
}
