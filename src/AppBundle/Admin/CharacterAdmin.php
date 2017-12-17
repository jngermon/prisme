<?php

namespace AppBundle\Admin;

use AppBundle\Entity\User;
use Greg0ire\Enum\Bridge\Symfony\Form\Type\EnumType;
use Knp\Menu\ItemInterface as MenuItemInterface;
use MMC\SonataAdminBundle\Datagrid\DTOFieldDescription;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class CharacterAdmin extends BaseAdmin
{
    protected $baseRouteName = 'app_admin_character';
    protected $baseRoutePattern = 'characters';

    protected $datagridValues = array(
        '_page' => 1,
        '_sort_order' => 'ASC',
        '_sort_by' => 'name',
    );

    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->with('bloc.identity', [
                'class'       => 'col-md-7',
                'box_class'   => 'box box-primary',
            ])
                ->add('player', null, ['route' => ['name' => 'show']])
                ->add('name')
                ->add('title')
            ->end()
            ->with('bloc.info', [
                'class'       => 'col-md-5',
                'box_class'   => 'box box-default',
            ])
                ->add('createdAt')
                ->add('updatedAt')
            ->end()
            ;
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('bloc.identity', [
                'class'       => '',
                'box_class'   => 'box box-primary',
            ])
                ->add('player', null, [
                    'required' => false,
                ])
                ->add('name')
                ->add('title')
            ->end()
            ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name', 'doctrine_orm_istring')
            ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('name')
            ->add('title')
            ->add('player', null, ['route' => ['name' => 'show']])
            ->add('_action', null, [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                ]
            ])
            ;
    }

    public function getExportFields()
    {
        return [
            'name' => new DTOFieldDescription('name'),
            'title' => new DTOFieldDescription('title'),
            'created_at' => new DTOFieldDescription('createdAt', 'datetime'),
        ];
    }
}
