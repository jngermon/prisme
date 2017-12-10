<?php

namespace ExternalBundle\Entity;

use \Doctrine\ORM\EntityRepository;

class SynchronizationRepository extends EntityRepository
{
    public function countRunning()
    {
        $qb = $this->createQueryBuilder('s')
            ->select('count(s)')
            ->andWhere('s.status = :status')
            ->setParameter('status', Enum\SynchronizationStatus::PROCESSING)
            ;

        return $qb->getQuery()->getSingleScalarResult();
    }

    public function getNext()
    {
        $qb = $this->createQueryBuilder('s')
            ->select('s')
            ->andWhere('s.status = :status')
            ->setParameter('status', Enum\SynchronizationStatus::PENDING)
            ->orderBy('s.createdAt', 'asc')
            ->setMaxResults(1)
            ;

        return $qb->getQuery()->getOneOrNullResult();
    }
}
