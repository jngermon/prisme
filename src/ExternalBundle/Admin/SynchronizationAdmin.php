<?php

namespace ExternalBundle\Admin;

use AppBundle\Admin\BaseAdmin;
use AppBundle\Entity\User;
use ExternalBundle\Entity\Enum\SynchronizationStatus;
use Greg0ire\Enum\Bridge\Symfony\Form\Type\EnumType;
use Knp\Menu\ItemInterface as MenuItemInterface;
use MMC\SonataAdminBundle\Datagrid\DTOFieldDescription;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class SynchronizationAdmin extends BaseAdmin
{
    protected $baseRouteName = 'app_admin_synchronization';
    protected $baseRoutePattern = 'synchronizations';

    protected $datagridValues = array(
        '_page' => 1,
        '_sort_order' => 'DESC',
        '_sort_by' => 'updatedAt',
    );

    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->with('bloc.process', [
                'class'       => 'col-md-7',
                'box_class'   => 'box box-primary',
            ])
                ->add('status', null, [
                    'template' => 'ExternalBundle:Admin:CRUD/show_synchronization_status.html.twig',
                ])
                ->add('options', null, [
                    'template' => 'ExternalBundle:Admin:CRUD/show_synchronization_options.html.twig',
                ])
                ->add('startedAt')
                ->add('endedAt')
            ->end()
            ->with('bloc.info', [
                'class'       => 'col-md-5',
                'box_class'   => 'box box-default',
            ])
                ->add('createdAt')
                ->add('updatedAt')
            ->end()
            ;

        if ($this->getSubject()->getStatus() == SynchronizationStatus::ERROR) {
            $showMapper
                ->with('bloc.process')
                    ->add('errors')
                ->end();
        }
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('bloc.process', [
                'class'       => '',
                'box_class'   => 'box box-primary',
            ])
                ->add('status')
                ->add('options')
            ->end()
            ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('status')
            ->add('createdAt')
            ->add('updatedAt')
            ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('createdAt')
            ->add('status', null, [
                'template' => 'ExternalBundle:Admin:CRUD/list_synchronization_status.html.twig',
            ])
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
            'status' => new DTOFieldDescription('status'),
            'options' => new DTOFieldDescription('options'),
            'created_at' => new DTOFieldDescription('createdAt', 'datetime'),
        ];
    }
}
