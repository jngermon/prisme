<?php

namespace ExternalBundle\Domain\Import\Common;

use Ddeboer\DataImport\ValueConverter\DateTimeValueConverter as BaseDateTimeValueConverter;

class DateTimeValueConverter extends BaseDateTimeValueConverter
{
    public function __invoke($input)
    {
        $date = parent::__invoke($input);

        if ($date instanceof \Datetime && $date->format('Y') < 1900) {
            return null;
        }

        return $date;
    }
}
