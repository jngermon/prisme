<?php

namespace AppBundle\Domain\CharacterDataDefinition\Viewer;

use AppBundle\Entity\CharacterDataDefinitionEnum;
use Doctrine\ORM\EntityRepository;

class EnumViewer extends BaseViewer
{
    protected $repository;

    public function __construct(
        EntityRepository $repository
    ) {
        $this->repository = $repository;
    }

    protected function createOptionResolver()
    {
        $resolver = parent::createOptionResolver();

        $resolver->setAllowedTypes('definition', CharacterDataDefinitionEnum::class);

        return $resolver;
    }

    protected function doProcess($request)
    {
        $character = $request['character'];
        $definition = $request['definition'];

        $id = intval($character->getData($definition->getName()));

        return $id ? $this->repository->findOneById($id) : null;
    }
}
