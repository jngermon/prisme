<?php

namespace AppBundle\Domain\CharacterDataDefinition\Validator;

use AppBundle\Entity\CharacterDataDefinition;
use AppBundle\Entity\CharacterDataDefinitionEnum;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Expression;

class EnumValidator extends BaseValidator
{
    protected function createOptionResolver()
    {
        $resolver = parent::createOptionResolver();

        $resolver->setAllowedTypes('definition', CharacterDataDefinitionEnum::class);

        return $resolver;
    }

    protected function getConstraints(CharacterDataDefinition $definition)
    {
        $constraints = parent::getConstraints($definition);

        $values = array_map(function ($item) {
            return $item->getId();
        }, $definition->getCategory()->getItems()->toArray());

        $constraints[] = new Choice([
            'choices' => $values,
        ]);

        return $constraints;
    }
}
