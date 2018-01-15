<?php

namespace AppBundle\Domain\LarpParameters\Normalizer;

use AppBundle\Entity\Calendar;
use AppBundle\Entity\CalendarMonth;
use Doctrine\Common\Inflector\Inflector;
use Symfony\Component\PropertyAccess\PropertyAccess;

class CalendarMonthDenormalizer extends BaseDenormalizer
{
    /**
     * {@inheritdoc}
     */
    public function denormalize($data, $class, $format = null, array $context = [])
    {
        $data = parent::denormalize($data, $class, $format, $context);

        $month = new CalendarMonth();

        if (isset($context['calendar'])) {
            $month->setCalendar($context['calendar']);
        }

        $accessor = PropertyAccess::createPropertyAccessor();
        foreach (['name', 'nbDays', 'position'] as $property) {
            if (array_key_exists($property, $data)) {
                $accessor->setValue($month, Inflector::camelize($property), $data[$property]);
            }
        }

        return $month;
    }

    protected function getSupportedClassname()
    {
        return CalendarMonth::class;
    }
}
