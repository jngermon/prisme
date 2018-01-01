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
        if (!preg_match('/^([0-9]+)\-([0-9]+)$/', $input, $matches)) {
            throw new Exception('Player id for converter have to be format idu-idgn');
        }

        $idu = $matches[1];
        $idgn = $matches[2];

        $qb = $this->repository->createQueryBuilder('p')
            ->innerJoin('p.person', 'pp')
            ->innerJoin('p.larp', 'l')
            ->andWhere('pp.externalId = :idu')
            ->setParameter('idu', $idu)
            ->andWhere('l.externalId = :idgn')
            ->setParameter('idgn', $idgn)
            ;

        return $qb->getQuery()->getOneOrNullResult();
    }
}
