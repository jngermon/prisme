<?php

namespace AppBundle\Domain\LarpParameters\Normalizer;

use AppBundle\Entity\Calendar;
use AppBundle\Entity\CalendarMonth;
use Doctrine\Common\Inflector\Inflector;
use Symfony\Component\PropertyAccess\PropertyAccess;

class CalendarDenormalizer extends BaseDenormalizer
{
    /**
     * {@inheritdoc}
     */
    public function denormalize($data, $class, $format = null, array $context = [])
    {
        $data = parent::denormalize($data, $class, $format, $context);

        $calendar = new Calendar();

        if (isset($context['larp'])) {
            $calendar->setLarp($context['larp']);
        }

        $accessor = PropertyAccess::createPropertyAccessor();
        foreach (['name', 'diffDaysWithOrigin', 'formatGlobal', 'formatYear', 'formatDay'] as $property) {
            if (array_key_exists($property, $data)) {
                $accessor->setValue($calendar, Inflector::camelize($property), $data[$property]);
            }
        }

        if (isset($data['months'])) {
            $calendar->setMonths($this->serializer->denormalize($data['months'], CalendarMonth::class.'[]', $format, array_merge($context, ['calendar' => $calendar])));
        }

        return $calendar;
    }

    protected function getSupportedClassname()
    {
        return Calendar::class;
    }
}
