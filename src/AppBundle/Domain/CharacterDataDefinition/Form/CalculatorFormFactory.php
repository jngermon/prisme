<?php

namespace AppBundle\Domain\CharacterDataDefinition\Form;

use AppBundle\Domain\CharacterDataDefinition\Calculator\Calculator;
use AppBundle\Entity\CharacterDataDefinition;
use AppBundle\Entity\CharacterDataDefinitionCalculator;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormFactory;

class CalculatorFormFactory extends BaseFormFactory
{
    protected $calculator;

    public function __construct(
        FormFactory $factory,
        Calculator $calculator
    ) {
        parent::__construct($factory);

        $this->calculator = $calculator;
    }

    public function supports($request)
    {
        return $request instanceof CharacterDataDefinitionCalculator;
    }

    protected function getTypeClass(CharacterDataDefinition $definition)
    {
        return TextType::class;
    }

    protected function doProcess($request)
    {
        $definition = $request;

        return $this->factory->createNamedBuilder($definition->getName(), $this->getTypeClass($definition), null, $this->getOptions($definition));
    }

    protected function getOptions(CharacterDataDefinition $definition)
    {
        return array_merge_recursive(parent::getOptions($definition), [
            'disabled' => true,
            'attr' => [
                'class' => 'calculator calculator_'.$definition->getProcessor(),
                'data-mapping' => json_encode($definition->getMapping()),
            ],
        ], $this->calculator->getProcessorFormOptions($definition->getProcessor(), $definition));
    }
}
