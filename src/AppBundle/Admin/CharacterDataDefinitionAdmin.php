<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class CharacterDataDefinitionAdmin extends BaseAdmin
{
    protected $baseRouteName = 'app_admin_character_data_definition';
    protected $baseRoutePattern = 'character_data_definition';

    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->with('bloc.main', [
                'class'       => 'col-md-6',
                'box_class'   => 'box box-primary',
            ])
                ->add('position')
                ->add('name')
                ->add('type', 'trans', [
                    'catalogue' => 'CharacterDataDefinition',
                ])
                ->add('label')
                ->add('min')
                ->add('max')
            ->end()
            ->with('bloc.options', [
                    'class'       => 'col-md-6',
                    'box_class'   => 'box box-primary',
                ])
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
                ->add('name')
                ->add('label')
                ->add('min')
                ->add('max')
            ->end()
            ->with('bloc.options', [
                    'class'       => 'col-md-6',
                    'box_class'   => 'box box-primary',
                ])
            ->end()
            ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('position')
            ->add('name')
            ->add('type', 'trans')
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
