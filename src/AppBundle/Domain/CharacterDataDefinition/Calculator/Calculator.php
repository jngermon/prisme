<?php

namespace AppBundle\Domain\CharacterDataDefinition\Calculator;

use AppBundle\Entity\Character;
use AppBundle\Entity\CharacterDataDefinition;
use Mmc\Processor\Component\ChainProcessor;

class Calculator extends ChainProcessor
{
    public function getValue(Character $character, CharacterDataDefinition $definition)
    {
        $request = [
            'character' => $character,
            'definition' => $definition,
        ];

        $response = $this->process($request);

        if ($response->isSuccessed()) {
            return $response->getOutput();
        }

        return ;
    }

    public function getProcessorNames()
    {
        $names = [];
        foreach ($this->processors as $item) {
            $names[] = $item->getProcessor()->getProcessorName();
        }

        return $names;
    }

    public function getProcessorMapping($processorName)
    {
        foreach ($this->processors as $item) {
            $processor = $item->getProcessor();
            if ($processorName == $processor->getProcessorName()) {
                return $processor->getMapping();
            }
        }

        return [];
    }

    public function getProcessorFormOptions($processorName, CharacterDataDefinition $definition)
    {
        foreach ($this->processors as $item) {
            $processor = $item->getProcessor();
            if ($processorName == $processor->getProcessorName()) {
                return $processor->getFormOptions($definition);
            }
        }

        return [];
    }
}
