<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Datagrid\ListMapper;

class CharacterOrganizerForOrganizerAdmin extends AbstractCharacterOrganizerAdmin
{
    protected $parentAssociationMapping = 'organizer';

    protected $baseRouteName = 'app_admin_character_organizer_for_organizer';
    protected $baseRoutePattern = 'character_organizer_for_organizers';

    protected $datagridValues = array(
        '_page' => 1,
        '_sort_order' => 'ASC',
        '_sort_by' => 'character.name',
    );

    public function toString($object)
    {
        return $object->getCharacter()->__toString();
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        parent::configureListFields($listMapper);

        $listMapper
            ->remove('organizer')
            ;
    }
}
