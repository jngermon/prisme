<?php

namespace AppBundle\Domain\LarpParameters\Normalizer;

use AppBundle\Entity\CharacterDataDefinitionEnumCategoryItem;
use Doctrine\Common\Inflector\Inflector;
use Symfony\Component\PropertyAccess\PropertyAccess;

class CharacterDataDefinitionEnumCategoryItemDenormalizer extends BaseDenormalizer
{
    /**
     * {@inheritdoc}
     */
    public function denormalize($data, $class, $format = null, array $context = [])
    {
        $data = parent::denormalize($data, $class, $format, $context);

        $item = new CharacterDataDefinitionEnumCategoryItem();

        if (isset($context['category'])) {
            $item->setCategory($context['category']);
        }

        $accessor = PropertyAccess::createPropertyAccessor();
        foreach (['name', 'label', 'position'] as $property) {
            if (array_key_exists($property, $data)) {
                $accessor->setValue($item, Inflector::camelize($property), $data[$property]);
            }
        }

        return $item;
    }

    protected function getSupportedClassname()
    {
        return CharacterDataDefinitionEnumCategoryItem::class;
    }
}
