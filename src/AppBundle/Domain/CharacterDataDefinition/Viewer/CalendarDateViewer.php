<?php

namespace AppBundle\Domain\CharacterDataDefinition\Viewer;

use AppBundle\Domain\Calendar\Formatter;
use AppBundle\Entity\CharacterDataDefinitionCalendarDate;

class CalendarDateViewer extends BaseViewer
{
    public function __construct(
        Formatter $formatter
    ) {
        $this->formatter = $formatter;
    }

    protected function createOptionResolver()
    {
        $resolver = parent::createOptionResolver();

        $resolver->setAllowedTypes('definition', CharacterDataDefinitionCalendarDate::class);

        return $resolver;
    }

    protected function doProcess($request)
    {
        $character = $request['character'];
        $definition = $request['definition'];

        return $this->formatter->formatDate($character->getData($definition->getName()));
    }
}
