<?php

namespace AppBundle\Domain\CharacterDataDefinition\Form;

use AppBundle\Domain\Calendar\Model\Date;
use AppBundle\Domain\Calendar\Form\Type\CalendarDateType;
use AppBundle\Entity\CharacterDataDefinition;
use AppBundle\Entity\CharacterDataDefinitionCalendarDate;

class CalendarDateFormFactory extends BaseFormFactory
{
    public function supports($request)
    {
        return $request instanceof CharacterDataDefinitionCalendarDate;
    }

    protected function getTypeClass(CharacterDataDefinition $definition)
    {
        return CalendarDateType::class;
    }

    protected function getOptions(CharacterDataDefinition $definition)
    {
        return array_merge_recursive(parent::getOptions($definition), [
            'larp' => $definition->getSection()->getLarp(),
        ]);
    }
}
