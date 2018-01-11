<?php

namespace AppBundle\Domain\CharacterDataDefinition\Form;

use AppBundle\Entity\CharacterDataDefinition;
use AppBundle\Entity\CharacterDataDefinitionBoolean;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class BooleanFormFactory extends BaseFormFactory
{
    public function supports($request)
    {
        return $request instanceof CharacterDataDefinitionBoolean;
    }

    protected function getTypeClass(CharacterDataDefinition $definition)
    {
        return CheckboxType::class;
    }
}
