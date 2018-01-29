<?php

namespace AppBundle\Domain\Calendar;

use AppBundle\Entity\Calendar;
use AppBundle\Entity\Larp;
use Doctrine\ORM\EntityManagerInterface;

class CalendarProvider
{
    protected $repository;

    public function __construct(
        EntityManagerInterface $em
    ) {
        $this->repository = $em->getRepository(Calendar::class);
    }

    public function findByLarp(Larp $larp)
    {
        $qb = $this->repository->createQueryBuilder('c')
            ->andWhere('c.larp = :larp')
            ->setParameter('larp', $larp)
            ->addOrderBy('c.name', 'asc')
            ;

        return $qb->getQuery()->getResult();
    }
}
