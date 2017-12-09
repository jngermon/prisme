<?php

namespace ExternalBundle\Domain\Import\Player;

use Doctrine\ORM\EntityRepository;

class PlayerConverter
{
    protected $repository;

    public function __construct(
        EntityRepository $repository
    ) {
        $this->repository = $repository;
    }

    public function __invoke($input)
    {
        $qb = $this->repository->createQueryBuilder('p')
            ->innerJoin('p.person', 'pp')
            ->andWhere('pp.externalId = :id')
            ->setParameter('id', $input)
            ;

        return $qb->getQuery()->getOneOrNullResult();
    }
}
