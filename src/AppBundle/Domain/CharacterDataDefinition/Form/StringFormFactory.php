<?php

namespace AppBundle\Domain\CharacterDataDefinition\Form;

use AppBundle\Entity\CharacterDataDefinition;
use AppBundle\Entity\CharacterDataDefinitionString;

class StringFormFactory extends BaseFormFactory
{
    public function supports($request)
    {
        return $request instanceof CharacterDataDefinitionString;
    }

    protected function getOptions(CharacterDataDefinition $definition)
    {
        return array_merge_recursive(parent::getOptions($definition), [
            'attr' => [
                'maxLength' => $definition->getOption('maxLength'),
            ],
        ]);
    }
}
