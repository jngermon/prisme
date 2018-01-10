<?php

namespace AppBundle\Domain\CharacterDataDefinition\Validator;

use AppBundle\Entity\CharacterDataDefinition;
use AppBundle\Entity\CharacterDataDefinitionString;
use Symfony\Component\Validator\Constraints\Length;

class StringValidator extends BaseValidator
{
    protected function createOptionResolver()
    {
        $resolver = parent::createOptionResolver();

        $resolver->setAllowedTypes('definition', CharacterDataDefinitionString::class);

        return $resolver;
    }

    protected function getConstraints(CharacterDataDefinition $definition)
    {
        $constraints = parent::getConstraints($definition);

        if ($definition->getOption('maxLength')) {
            $constraints[] = new Length([
                'max' => $definition->getOption('maxLength'),
            ]);
        }

        return $constraints;
    }
}
