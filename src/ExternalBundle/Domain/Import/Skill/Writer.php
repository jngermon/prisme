<?php

namespace ExternalBundle\Domain\Import\Skill;

use AppBundle\Entity\Skill;
use ExternalBundle\Domain\Import\Common\Writer as BaseWriter;
use Doctrine\ORM\EntityManager;

class Writer extends BaseWriter
{
    protected $em;
    public function __construct(
        EntityManager $em
    ) {
        parent::__construct($em, Skill::class);
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

    protected function createMarkEntitiesQueryBuilder()
    {
        $queryBuilder = parent::createMarkEntitiesQueryBuilder();

        if (!empty($this->optionsProcessing['larp_id'])) {
            $queryBuilder
                ->andWhere('x.larp = :lExternalId')
                ->setParameter('lExternalId', $this->optionsProcessing['larp_id'])
                ;
        }

        return $queryBuilder;
    }
}
