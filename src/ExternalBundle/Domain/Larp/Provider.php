<?php

namespace ExternalBundle\Domain\Larp;

use ExternalBundle\Entity\Larp;
use Doctrine\ORM\EntityRepository;

class Provider
{
    protected $repository;

    protected $larps = null;

    public function __construct(
        EntityRepository $repository
    ) {
        $this->repository = $repository;

        if ($repository->getClassName() != Larp::class) {
            throw new \RuntimeException('Bad repository for Larp Provider');
        }
    }

    public function getAll()
    {
        if ($this->larps === null) {
            $this->larps = $this->loadAll();
        }

        return $this->larps;
    }

    protected function loadAll()
    {
        $qb = $this->repository
            ->createQueryBuilder('g')
            ->orderBy('g.startedAt', 'desc')
            ;

        return $qb->getQuery()->getResult();
    }
}
