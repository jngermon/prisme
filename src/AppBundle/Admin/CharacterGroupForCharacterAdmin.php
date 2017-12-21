<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Datagrid\ListMapper;

class CharacterGroupForCharacterAdmin extends AbstractCharacterGroupAdmin
{
    protected $parentAssociationMapping = 'character';

    protected $baseRouteName = 'app_admin_character_group_for_character';
    protected $baseRoutePattern = 'character_group_for_characters';

    protected $datagridValues = array(
        '_page' => 1,
        '_sort_order' => 'ASC',
        '_sort_by' => 'group.name',
    );

    public function toString($object)
    {
        return $object->getGroup()->__toString();
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
