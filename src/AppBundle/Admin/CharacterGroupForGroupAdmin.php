<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Datagrid\ListMapper;

class CharacterGroupForGroupAdmin extends AbstractCharacterGroupAdmin
{
    protected $parentAssociationMapping = 'group';

    protected $baseRouteName = 'app_admin_character_group_for_group';
    protected $baseRoutePattern = 'character_group_for_groups';

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
            ->remove('group')
            ;
    }
}
