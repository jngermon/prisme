<?php

namespace AppBundle\Domain\CharacterDataDefinition\Viewer;

use AppBundle\Entity\CharacterDataDefinitionBoolean;

class BooleanViewer extends BaseViewer
{
    protected function createOptionResolver()
    {
        $resolver = parent::createOptionResolver();

        $resolver->setAllowedTypes('definition', CharacterDataDefinitionBoolean::class);

        return $resolver;
    }

    protected function doProcess($request)
    {
        $character = $request['character'];
        $definition = $request['definition'];

        return $character->getData($definition->getName()) ? $definition->getTrue() : $definition->getFalse();
    }
}
