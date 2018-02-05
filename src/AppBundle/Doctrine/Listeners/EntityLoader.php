<?php

namespace AppBundle\Doctrine\Listeners;

use AppBundle\Doctrine\Common\EntityLoadEventArgs;
use Doctrine\ORM\EntityManager as Container;

class EntityLoader
{
    protected $container;

    public function __construct(
        Container $container
    ) {
        $this->container = $container;
    }

    public function loadEntity(EntityLoadEventArgs $args)
    {
        $entity = $this->container->getRepository($args->getClassname())->findOneById($args->getId());

        $args->setEntity($entity);
    }
}
