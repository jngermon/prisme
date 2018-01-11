<?php

namespace AppBundle\Domain\CharacterDataDefinition\Validator;

use AppBundle\Entity\CharacterDataDefinition;
use AppBundle\Entity\CharacterDataDefinitionBoolean;
use Symfony\Component\Validator\Constraints\Length;

class BooleanValidator extends BaseValidator
{
    protected function createOptionResolver()
    {
        $resolver = parent::createOptionResolver();

        $resolver->setAllowedTypes('definition', CharacterDataDefinitionBoolean::class);

        return $resolver;
    }
}
