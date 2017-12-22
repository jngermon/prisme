<?php

namespace ExternalBundle\Domain\Import\CharacterOrganizer;

use AppBundle\Entity\CharacterOrganizer;
use ExternalBundle\Domain\Import\Common\Writer as BaseWriter;
use Doctrine\ORM\EntityManager;

class Writer extends BaseWriter
{
    protected $em;
    public function __construct(
        EntityManager $em
    ) {
        parent::__construct($em, CharacterOrganizer::class);
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

    protected function createMarkEntitiesQueryBuilder()
    {
        $queryBuilder = parent::createMarkEntitiesQueryBuilder();

        if (!empty($this->optionsProcessing['character_id'])) {
            $queryBuilder
                ->andWhere('x.character = :cExternalId')
                ->setParameter('cExternalId', $this->optionsProcessing['character_id'])
                ;
        }

        if (!empty($this->optionsProcessing['organizer_id'])) {
            $queryBuilder
                ->andWhere('x.organizer = :gExternalId')
                ->setParameter('gExternalId', $this->optionsProcessing['organizer_id'])
                ;
        }

        if (!empty($this->optionsProcessing['larp_id'])) {

            $subQB = $this->entityRepository->createQueryBuilder('x2')
                ->select('x2.id')
                ->innerJoin('x2.organizer', 'o')
                ->andWhere('o.larp = :lExternalId');


            $queryBuilder
                ->andWhere($queryBuilder->expr()->in('x.id', $subQB->getDQL()))
                ->setParameter('lExternalId', $this->optionsProcessing['larp_id'])
                ;
        }

        return $queryBuilder;
    }
}
