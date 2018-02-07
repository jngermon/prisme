<?php

namespace AppBundle\Domain\CharacterDataDefinition\Form;

use AppBundle\Entity\CharacterDataDefinition;
use AppBundle\Entity\CharacterDataDefinitionInteger;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class IntegerFormFactory extends BaseFormFactory
{
    public function supports($request)
    {
        return $request instanceof CharacterDataDefinitionInteger;
    }

    protected function getTypeClass(CharacterDataDefinition $definition)
    {
        return IntegerType::class;
    }

    protected function getOptions(CharacterDataDefinition $definition)
    {
        return array_merge_recursive(parent::getOptions($definition), [
            'attr' => [
                'min' => $definition->getOption('min'),
                'max' => $definition->getOption('max'),
            ],
        ]);
    }
}
