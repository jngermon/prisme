<?php

namespace AppBundle\Domain\LarpParameters\Normalizer;

use AppBundle\Entity\CharacterDataDefinition;
use AppBundle\Entity\CharacterDataSection;
use Doctrine\Common\Inflector\Inflector;
use Symfony\Component\PropertyAccess\PropertyAccess;

class CharacterDataSectionDenormalizer extends BaseDenormalizer
{
    /**
     * {@inheritdoc}
     */
    public function denormalize($data, $class, $format = null, array $context = [])
    {
        $data = parent::denormalize($data, $class, $format, $context);


        $section = new CharacterDataSection();

        if (isset($context['larp'])) {
            $section->setLarp($context['larp']);
        }

        if (isset($data['characterDataDefinitions'])) {
            $section->setCharacterDataDefinitions($this->serializer->denormalize($data['characterDataDefinitions'], CharacterDataDefinition::class.'[]', $format, array_merge($context, ['section' => $section])));
        }

        $accessor = PropertyAccess::createPropertyAccessor();
        foreach (['label', 'position', 'size'] as $property) {
            if (array_key_exists($property, $data)) {
                $accessor->setValue($section, Inflector::camelize($property), $data[$property]);
            }
        }

        return $section;
    }

    protected function getSupportedClassname()
    {
        return CharacterDataSection::class;
    }
}
