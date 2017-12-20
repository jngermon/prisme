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

class PlayerAdmin extends BaseAdmin
{
    protected $baseRouteName = 'app_admin_player';
    protected $baseRoutePattern = 'players';

    protected $datagridValues = array(
        '_page' => 1,
        '_sort_order' => 'ASC',
        '_sort_by' => 'person.lastname',
    );

    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->with('bloc.person', [
                'class'       => 'col-md-7',
                'box_class'   => 'box box-primary',
            ])
                ->add('person')
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
            ->with('bloc.person', [
                'class'       => '',
                'box_class'   => 'box box-primary',
            ])
                ->add('person')
            ->end()
            ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('person.firstname', 'doctrine_orm_istring')
            ->add('person.lastname', 'doctrine_orm_istring')
            ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('person')
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
            'person' => new DTOFieldDescription('person'),
            'created_at' => new DTOFieldDescription('createdAt', 'datetime'),
        ];
    }
}
