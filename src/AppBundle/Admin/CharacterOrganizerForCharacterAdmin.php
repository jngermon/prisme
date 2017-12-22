<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Datagrid\ListMapper;

class CharacterOrganizerForCharacterAdmin extends AbstractCharacterOrganizerAdmin
{
    protected $parentAssociationMapping = 'character';

    protected $baseRouteName = 'app_admin_character_organizer_for_character';
    protected $baseRoutePattern = 'character_organizer_for_characters';

    protected $datagridValues = array(
        '_page' => 1,
        '_sort_order' => 'ASC',
        '_sort_by' => 'organizer.name',
    );

    public function toString($object)
    {
        return $object->getOrganizer()->__toString();
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        parent::configureListFields($listMapper);

        $listMapper
            ->remove('character')
            ->remove('character.player')
            ;
    }
}
