<?php

namespace AppBundle\Domain\CharacterDataDefinition\Transformer;

use AppBundle\Entity\CharacterDataDefinitionCalculator;
use AppBundle\Domain\CharacterDataDefinition\Calculator\Calculator;

class CalculatorTransformer extends BaseTransformer
{
    protected $calculator;

    public function __construct(Calculator $calculator)
    {
        $this->calculator = $calculator;
    }

    protected function createOptionResolver($reverse = false)
    {
        $resolver = parent::createOptionResolver();

        $resolver->setAllowedTypes('definition', CharacterDataDefinitionCalculator::class);

        return $resolver;
    }

    protected function doProcess($request)
    {
        $value = $request['value'];
        $definition = $request['definition'];
        $character = $request['character'];
        $reverse = $request['reverse'];

        if ($reverse) {
            return $value;
        }

        return $this->calculator->getValue($character, $definition);
    }
}
