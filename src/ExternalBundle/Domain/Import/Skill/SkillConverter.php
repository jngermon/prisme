<?php

namespace ExternalBundle\Domain\Import\Skill;

use Doctrine\ORM\EntityRepository;

class SkillConverter
{
    protected $repository;

    public function __construct(
        EntityRepository $repository
    ) {
        $this->repository = $repository;
    }

    public function __invoke($input)
    {
        $qb = $this->repository->createQueryBuilder('s')
            ->andWhere('s.externalId = :id')
            ->setParameter('id', $input)
            ;

        return $qb->getQuery()->getOneOrNullResult();
    }
}
