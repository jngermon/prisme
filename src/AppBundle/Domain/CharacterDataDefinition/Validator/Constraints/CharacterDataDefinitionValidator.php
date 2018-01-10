<?php

namespace AppBundle\Domain\CharacterDataDefinition\Validator\Constraints;

use Mmc\Processor\Component\ChainProcessorTrait;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class CharacterDataDefinitionValidator extends ConstraintValidator
{
    use ChainProcessorTrait;

    public function validate($value, Constraint $constraint)
    {
        $definitions = $value->getLarp()->getCharacterDataDefinitions();

        foreach ($definitions as $definition) {
            $request = [
                'character' => $value,
                'definition' => $definition,
                'context' => $this->context,
            ];

            $this->doProcess($request);
        }
    }
}
