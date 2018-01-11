<?php

namespace AppBundle\Domain\CharacterDataDefinition\Form;

use AppBundle\Entity\CharacterDataDefinition;
use Mmc\Processor\Component\Processor;
use Mmc\Processor\Component\ProcessorTrait;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormFactory;

class BaseFormFactory implements Processor
{
    use ProcessorTrait;

    protected $factory;

    public function __construct(
        FormFactory $factory
    ) {
        $this->factory = $factory;
    }

    public function supports($request)
    {
        return $request instanceof CharacterDataDefinition;
    }

    protected function doProcess($request)
    {
        $definition = $request;

        return $this->factory->createNamedBuilder($definition->getName(), $this->getTypeClass($definition), null, $this->getOptions($definition));
    }

    protected function getTypeClass(CharacterDataDefinition $definition)
    {
        return TextType::class;
    }

    protected function getOptions(CharacterDataDefinition $definition)
    {
        return [
            'label' => $definition->getLabel(),
            'required' => $definition->getRequired(),
            'property_path' => 'datas['.$definition->getName().']',
        ];
    }
}
