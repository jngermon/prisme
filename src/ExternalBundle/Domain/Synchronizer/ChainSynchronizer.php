<?php

namespace ExternalBundle\Domain\Synchronizer;

use Mmc\Processor\Component\ChainProcessor;

class ChainSynchronizer extends ChainProcessor implements SynchronizerInterface
{
    public function getMappings($request)
    {
        $item =$this->findItem($request);

        if ($item) {
            return $item->getProcessor()->getMappings($request);
        }
    }
}
