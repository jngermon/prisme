<?php

namespace AppBundle\Domain\CharacterDataDefinition\Calculator;

use AppBundle\Entity\Character;
use AppBundle\Entity\CharacterDataDefinition;
use AppBundle\Entity\CharacterDataDefinitionCalculator;
use Mmc\Processor\Component\Processor;
use Mmc\Processor\Component\ProcessorTrait;
use Symfony\Component\OptionsResolver\Exception\InvalidArgumentException;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class BaseCalculator implements Processor
{
    use ProcessorTrait;

    public function supports($request)
    {
        if (!is_array($request)) {
            return false;
        }

        try {
            $request = $this->createOptionResolver()->resolve($request);
        } catch (InvalidArgumentException $e) {
            return false;
        }

        return $request['definition']->getProcessor() == $this->getProcessorName();
    }

    abstract public function getProcessorName();

    abstract public function getMapping();

    public function getFormOptions(CharacterDataDefinition $definition)
    {
        return [];
    }

    abstract protected function doProcess($request);


    protected function createOptionResolver()
    {
        $resolver = new OptionsResolver();

        $resolver->setRequired(['character', 'definition']);

        $resolver->setAllowedTypes('character', Character::class);
        $resolver->setAllowedTypes('definition', CharacterDataDefinitionCalculator::class);

        return $resolver;
    }
}
