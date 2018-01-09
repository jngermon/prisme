<?php

namespace AppBundle\Domain\CharacterDataDefinition\Viewer;

use AppBundle\Entity\CharacterDataDefinitionInteger;

class IntegerViewer extends BaseViewer
{
    protected function createOptionResolver()
    {
        $resolver = parent::createOptionResolver();

        $resolver->setAllowedTypes('definition', CharacterDataDefinitionInteger::class);

        return $resolver;
    }

    protected function doProcess($request)
    {
        $character = $request['character'];
        $definition = $request['definition'];

        return intval($character->getData($definition->getName()));
    }
}
