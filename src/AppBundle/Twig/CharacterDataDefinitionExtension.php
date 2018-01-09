<?php

namespace AppBundle\Twig;

use AppBundle\Domain\CharacterDataDefinition\Viewer\Viewer;
use AppBundle\Entity\Character;
use AppBundle\Entity\CharacterDataDefinition;

class CharacterDataDefinitionExtension extends \Twig_Extension
{
    protected $viewer;

    public function __construct(
        Viewer $viewer
    ) {
        $this->viewer = $viewer;
    }


    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('valueByDefinition', [$this, 'valueByDefinition']),
        );
    }

    public function valueByDefinition(Character $character, CharacterDataDefinition $definition)
    {
        return $this->viewer->getValue($character, $definition);
    }

    public function getName()
    {
        return 'character_data_definition';
    }
}
