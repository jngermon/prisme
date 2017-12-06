<?php

namespace ExternalBundle\Domain\Import\Person;

use AppBundle\Entity\Enum\Gender;

class GenderConverter
{
    public function __invoke($input)
    {
        switch ($input) {
            case 'F':
                return Gender::FEMALE;
            case 'H':
            default:
                return Gender::MALE;
        }
    }
}
