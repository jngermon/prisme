<?php

namespace ExternalBundle\Domain\Larp;

use AppBundle\Entity\Larp;
use ExternalBundle\Entity\Larp as ExternalLarp;
use Doctrine\ORM\EntityManager;

class Creator
{
    protected $em;

    public function __construct(
        EntityManager $em
    ) {
        $this->em = $em;
    }

    public function createFromExternalLarp(ExternalLarp $externalLarp, $flush = true)
    {
        $larp = $this->em->getRepository(Larp::class)->findOneByExternalId($externalLarp->getId());
        if ($larp) {
            throw new \Exception(sprintf('The Larp with ID %s is already link to this external Larp', $larp->getId()));
        }

        $larp = new Larp();
        $larp->setExternalId($externalLarp->getId())
            ->setName($externalLarp->getName())
            ->setStartedAt($externalLarp->getStartedAt())
            ->setEndedAt($externalLarp->getEndedAt())
            ;

        $this->em->persist($larp);

        if ($flush) {
            $this->em->flush();
        }

        return $larp;
    }
}
