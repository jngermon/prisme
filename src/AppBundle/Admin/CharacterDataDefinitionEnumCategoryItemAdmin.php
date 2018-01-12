<?php

namespace AppBundle\Admin;

use Greg0ire\Enum\Bridge\Symfony\Form\Type\EnumType;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class CharacterDataDefinitionEnumCategoryItemAdmin extends BaseAdmin
{
    protected $parentAssociationMapping = 'category';

    protected $baseRouteName = 'app_admin_character_data_definition_enum_category_item';
    protected $baseRoutePattern = 'character_data_definition_enum_category_item';

    protected $datagridValues = array(
        '_page' => 1,
        '_sort_order' => 'ASC',
        '_sort_by' => 'position',
    );

    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->with('bloc.main', [
                'class'       => 'col-md-6',
                'box_class'   => 'box box-primary',
            ])
                ->add('position')
                ->add('name')
                ->add('label')
            ->end()
            ;
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('bloc.main', [
                'class'       => 'col-md-6',
                'box_class'   => 'box box-primary',
            ])
                ->add('position')
                ->add('name', null, [
                    'required' => false
                ])
                ->add('label')
            ->end()
            ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('position')
            ->add('name')
            ->add('label')
            ->add('_action', null, [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                ]
            ])
            ;
    }
}
