<?php

namespace AppBundle\Domain\CharacterDataDefinition\Validator;

use AppBundle\Entity\CharacterDataDefinition;
use AppBundle\Entity\CharacterDataDefinitionInteger;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;

class IntegerValidator extends BaseValidator
{
    protected function createOptionResolver()
    {
        $resolver = parent::createOptionResolver();

        $resolver->setAllowedTypes('definition', CharacterDataDefinitionInteger::class);

        return $resolver;
    }

    protected function getConstraints(CharacterDataDefinition $definition)
    {
        $constraints = parent::getConstraints($definition);

        if ($definition->getOption('min') !== null) {
            $constraints[] = new GreaterThanOrEqual($definition->getOption('min'));
        }

        if ($definition->getOption('max') !== null) {
            $constraints[] = new LessThanOrEqual($definition->getOption('max'));
        }

        return $constraints;
    }
}
