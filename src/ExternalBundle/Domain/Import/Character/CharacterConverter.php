<?php

namespace ExternalBundle\Domain\Import\Character;

use Doctrine\ORM\EntityRepository;

class CharacterConverter
{
    protected $repository;

    public function __construct(
        EntityRepository $repository
    ) {
        $this->repository = $repository;
    }

    public function __invoke($input)
    {
        $qb = $this->repository->createQueryBuilder('c')
            ->andWhere('c.externalId = :id')
            ->setParameter('id', $input)
            ;

        return $qb->getQuery()->getOneOrNullResult();
    }
}
