<?php

namespace AppBundle\Domain\CharacterDataDefinition\Transformer;

use AppBundle\Entity\Character;
use AppBundle\Entity\CharacterDataDefinition;
use Mmc\Processor\Component\ChainProcessor;

class Transformer extends ChainProcessor
{
    public function transform($value, CharacterDataDefinition $definition, Character $character)
    {
        $request = [
            'value' => $value,
            'definition' => $definition,
            'character' => $character,
            'reverse' => false,
        ];

        $response = $this->process($request);

        if ($response->isSuccessed()) {
            return $response->getOutput();
        }

        return $value;
    }

    public function reverseTransform($value, CharacterDataDefinition $definition, Character $character)
    {
        $request = [
            'value' => $value,
            'definition' => $definition,
            'character' => $character,
            'reverse' => true,
        ];

        $response = $this->process($request);

        if ($response->isSuccessed()) {
            return $response->getOutput();
        }

        return $value;
    }
}
