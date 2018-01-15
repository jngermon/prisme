<?php

namespace AppBundle\Domain\LarpParameters\Normalizer;

use AppBundle\Entity\CharacterDataDefinitionEnumCategory;
use AppBundle\Entity\CharacterDataDefinitionEnumCategoryItem;
use Doctrine\Common\Inflector\Inflector;
use Symfony\Component\PropertyAccess\PropertyAccess;

class CharacterDataDefinitionEnumCategoryDenormalizer extends BaseDenormalizer
{
    /**
     * {@inheritdoc}
     */
    public function denormalize($data, $class, $format = null, array $context = [])
    {
        $data = parent::denormalize($data, $class, $format, $context);

        $category = new CharacterDataDefinitionEnumCategory();

        if (isset($context['larp'])) {
            $category->setLarp($context['larp']);
        }

        $accessor = PropertyAccess::createPropertyAccessor();
        foreach (['name', 'label'] as $property) {
            if (array_key_exists($property, $data)) {
                $accessor->setValue($category, Inflector::camelize($property), $data[$property]);
            }
        }

        if (isset($data['items'])) {
            $category->setItems($this->serializer->denormalize($data['items'], CharacterDataDefinitionEnumCategoryItem::class.'[]', $format, array_merge($context, ['category' => $category])));
        }

        return $category;
    }

    protected function getSupportedClassname()
    {
        return CharacterDataDefinitionEnumCategory::class;
    }
}
