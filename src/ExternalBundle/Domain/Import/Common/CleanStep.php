<?php

namespace ExternalBundle\Domain\Import\Common;

use Ddeboer\DataImport\Step;

class CleanStep implements Step
{
    protected $autorizeFields;

    public function __construct(array $autorizeFields = [])
    {
        $this->autorizeFields = $autorizeFields;
    }

    public function process(&$item)
    {
        $diff = array_diff(array_keys($item), $this->autorizeFields);
        foreach ($diff as $extraField) {
            unset($item[$extraField]);
        }
    }
}
