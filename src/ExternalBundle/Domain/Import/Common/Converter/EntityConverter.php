<?php

namespace ExternalBundle\Domain\Import\Common\Converter;

use Doctrine\ORM\EntityRepository;

class EntityConverter
{
    protected $repository;

    public function __construct(
        EntityRepository $repository
    ) {
        $this->repository = $repository;
    }

    public function __invoke($input)
    {
        return $this->repository->findOneByExternalId($input);
    }
}
