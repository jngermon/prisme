<?php

namespace AppBundle\Domain\CharacterDataDefinition\Form;

use AppBundle\Entity\CharacterDataDefinition;
use Mmc\Processor\Component\ChainProcessor;

class FormFactory extends ChainProcessor
{
    public function create(CharacterDataDefinition $definition)
    {
        $response = $this->process($definition);

        if ($response->isSuccessed()) {
            return $response->getOutput();
        }

        return ;
    }
}
