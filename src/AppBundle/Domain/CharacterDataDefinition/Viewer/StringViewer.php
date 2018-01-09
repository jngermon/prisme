<?php

namespace AppBundle\Domain\CharacterDataDefinition\Viewer;

use AppBundle\Entity\CharacterDataDefinitionString;

class StringViewer extends BaseViewer
{
    protected function createOptionResolver()
    {
        $resolver = parent::createOptionResolver();

        $resolver->setAllowedTypes('definition', CharacterDataDefinitionString::class);

        return $resolver;
    }

    protected function doProcess($request)
    {
        $character = $request['character'];
        $definition = $request['definition'];

        return $character->getData($definition->getName());
    }
}
