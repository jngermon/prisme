<?php

namespace AppBundle\Domain\LarpParameters\Normalizer;

use AppBundle\Domain\LarpParameters\LarpParameters;
use AppBundle\Entity\Calendar;
use AppBundle\Entity\CharacterDataSection;
use AppBundle\Entity\CharacterDataDefinitionEnumCategory;

class LarpParametersDenormalizer extends BaseDenormalizer
{
    /**
     * {@inheritdoc}
     */
    public function denormalize($data, $class, $format = null, array $context = [])
    {
        $data = parent::denormalize($data, $class, $format, $context);

        $parameters = new LarpParameters();

        if (isset($data['calendars'])) {
            $parameters->setCalendars($this->serializer->denormalize($data['calendars'], Calendar::class.'[]', $format, $context));
        }

        $context = array_merge($context, [
            'calendars' => $parameters->getCalendars(),
        ]);

        if (isset($data['characterDataDefinitionEnumCategories'])) {
            $parameters->setCharacterDataDefinitionEnumCategories($this->serializer->denormalize($data['characterDataDefinitionEnumCategories'], CharacterDataDefinitionEnumCategory::class.'[]', $format, $context));
        }

        $context = array_merge($context, [
            'categories' => $parameters->getCharacterDataDefinitionEnumCategories(),
        ]);

        if (isset($data['characterDataSections'])) {
            $parameters->setCharacterDataSections($this->serializer->denormalize($data['characterDataSections'], CharacterDataSection::class.'[]', $format, $context));
        }

        return $parameters;
    }

    protected function getSupportedClassname()
    {
        return LarpParameters::class;
    }
}
