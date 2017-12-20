<?php

namespace AppBundle\Domain\Group;

use AppBundle\Entity\Group;
use Doctrine\ORM\EntityRepository;

class CompositionProvider
{
    protected $repository;

    public function __construct(
        EntityRepository $repository
    ) {
        $this->repository = $repository;
    }

    public function getCompositionForGroup(Group $group)
    {
        $qb = $this->repository->createQueryBuilder('g');
        $qb->select('SUM(CASE WHEN p.id IS NULL THEN 1 ELSE 0 END) as not_linked_to_player')
            ->addSelect('SUM(CASE WHEN p.id IS NULL THEN 0 ELSE 1 END) as linked_to_player')
            ->addSelect('COUNT(c) as total')
            ->innerJoin('g.characterGroups', 'cg')
            ->innerJoin('cg.character', 'c')
            ->leftJoin('c.player', 'p')
            ->andWhere('g = :group')
            ->setParameter('group', $group)
            ;

        return $qb->getQuery()->getSingleResult();
    }
}
