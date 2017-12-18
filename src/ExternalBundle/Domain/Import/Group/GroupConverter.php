<?php

namespace ExternalBundle\Domain\Import\Group;

use Doctrine\ORM\EntityRepository;

class GroupConverter
{
    protected $repository;

    public function __construct(
        EntityRepository $repository
    ) {
        $this->repository = $repository;
    }

    public function __invoke($input)
    {
        $qb = $this->repository->createQueryBuilder('g')
            ->andWhere('g.externalId = :id')
            ->setParameter('id', $input)
            ;

        return $qb->getQuery()->getOneOrNullResult();
    }
}
