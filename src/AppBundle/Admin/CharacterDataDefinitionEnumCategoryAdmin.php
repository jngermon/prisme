<?php

namespace AppBundle\Admin;

use Doctrine\ORM\EntityRepository;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class CharacterDataDefinitionEnumCategoryAdmin extends BaseAdmin
{
    protected $baseRouteName = 'app_admin_character_data_definition_enum_category';
    protected $baseRoutePattern = 'character_data_definition_enum_category';

    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->with('bloc.main', [
                'class'       => 'col-md-6',
                'box_class'   => 'box box-primary',
            ])
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
                ->add('name')
                ->add('label')
            ->end()
            ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
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
