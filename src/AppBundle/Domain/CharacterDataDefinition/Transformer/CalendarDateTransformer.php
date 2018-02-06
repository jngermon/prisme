<?php

namespace AppBundle\Domain\CharacterDataDefinition\Transformer;

use AppBundle\Domain\Calendar\Model\Date;
use AppBundle\Entity\Calendar;
use AppBundle\Entity\CharacterDataDefinitionCalendarDate;
use Doctrine\ORM\EntityManager;

class CalendarDateTransformer extends BaseTransformer
{
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    protected function createOptionResolver($reverse = false)
    {
        $resolver = parent::createOptionResolver();

        $resolver->setAllowedTypes('value', $reverse ? 'string' : Date::class);
        $resolver->setAllowedTypes('definition', CharacterDataDefinitionCalendarDate::class);

        return $resolver;
    }

    protected function doProcess($request)
    {
        $value = $request['value'];
        $definition = $request['definition'];
        $reverse = $request['reverse'];

        if ($reverse) {
            $date = new Date(0);

            if (preg_match('/^(\d+)(?:\:(\d+))?$/', $value, $matches)) {
                $calendar = null;
                if ($matches[2]) {
                    $calendar = $this->em->getRepository(Calendar::class)->findOneById($matches[2]);
                }

                $date = new Date($matches[1], $calendar);
            }

            return $date;
        }

        return $value->getNbDaysFromOrigin() . ':' . $value->getCalendar()->getId();
    }
}
