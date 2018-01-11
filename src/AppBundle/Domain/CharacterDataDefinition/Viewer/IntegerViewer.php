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

        $v = intval($character->getData($definition->getName()));

        $unity = $v > 1 ? $definition->getPlural() : $ $definition->getSingular();

        return sprintf('%d %s', $v, $unity);
    }
}
