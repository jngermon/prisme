<?php

namespace AppBundle\Domain\CharacterDataDefinition\Viewer;

use AppBundle\Entity\Character;
use AppBundle\Entity\CharacterDataDefinition;
use Mmc\Processor\Component\ChainProcessor;

class Viewer extends ChainProcessor
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
}
