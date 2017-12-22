<?php

namespace ExternalBundle\Domain\Import\Organizer;

use Doctrine\ORM\EntityRepository;

class OrganizerConverter
{
    protected $repository;

    public function __construct(
        EntityRepository $repository
    ) {
        $this->repository = $repository;
    }

    public function __invoke($input)
    {
        $qb = $this->repository->createQueryBuilder('o')
            ->andWhere('o.externalId = :id')
            ->setParameter('id', $input)
            ;

        return $qb->getQuery()->getOneOrNullResult();
    }
}
