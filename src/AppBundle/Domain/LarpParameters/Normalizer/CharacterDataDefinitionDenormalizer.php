<?php

namespace AppBundle\Domain\LarpParameters\Normalizer;

use AppBundle\Entity\CharacterDataDefinition;
use AppBundle\Entity\CharacterDataDefinitionEnum;
use Doctrine\Common\Inflector\Inflector;
use Symfony\Component\PropertyAccess\PropertyAccess;

class CharacterDataDefinitionDenormalizer extends BaseDenormalizer
{
    /**
     * {@inheritdoc}
     */
    public function denormalize($data, $class, $format = null, array $context = [])
    {
        $data = parent::denormalize($data, $class, $format, $context);

        if (!isset($data['type'])) {
            return null;
        }

        $definition = new $data['type']();

        if (isset($context['larp'])) {
            $definition->setLarp($context['larp']);
        }

        if (isset($context['section'])) {
            $definition->setSection($context['section']);
        }

        $accessor = PropertyAccess::createPropertyAccessor();
        foreach (['name', 'options', 'label', 'position', 'required'] as $property) {
            if (array_key_exists($property, $data)) {
                $accessor->setValue($definition, Inflector::camelize($property), $data[$property]);
            }
        }

        if ($definition instanceof CharacterDataDefinitionEnum && isset($data['categoryName']) && isset($context['categories'])) {
            foreach ($context['categories'] as $category) {
                if ($category->getName() == $data['categoryName']) {
                    $definition->setCategory($category);
                    break;
                }
            }
        }

        return $definition;
    }

    protected function getSupportedClassname()
    {
        return CharacterDataDefinition::class;
    }
}
