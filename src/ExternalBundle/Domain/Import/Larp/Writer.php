<?php

namespace ExternalBundle\Domain\Import\Larp;

use AppBundle\Entity\Larp;
use ExternalBundle\Domain\Import\Common\Writer as BaseWriter;
use Doctrine\ORM\EntityManager;

class Writer extends BaseWriter
{
    protected $em;
    public function __construct(
        EntityManager $em
    ) {
        parent::__construct($em, Larp::class);
    }
}
