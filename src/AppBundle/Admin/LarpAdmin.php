<?php

namespace AppBundle\Admin;

use MMC\SonataAdminBundle\Datagrid\DTOFieldDescription;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class LarpAdmin extends BaseAdmin
{
    protected $baseRouteName = 'app_admin_larp';
    protected $baseRoutePattern = 'larps';

    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->with('bloc.main', [
                'class'       => 'col-md-7',
                'box_class'   => 'box box-primary',
                'translation_domain' => 'Larp',
            ])
                ->add('name', 'text')
                ->add('startedAt')
                ->add('endedAt')
                ->add('owner')
            ->end()
            ;
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('bloc.main', [
                'class'       => 'col-md-7',
                'box_class'   => 'box box-primary',
                'translation_domain' => 'Larp',
            ])
                ->add('name', 'text')
                ->add('startedAt', 'sonata_type_datetime_picker', [
                    'required' => false,
                ])
                ->add('endedAt', 'sonata_type_datetime_picker', [
                    'required' => false,
                ])
                ->add('owner', null, ['disabled' => !$this->getSecurityHandler()->isGranted($this, 'CHANGE_OWNER', $this->getSubject())])
            ->end()
            ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('name', 'doctrine_orm_istring');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('name')
            ->add('startedAt')
            ->add('endedAt')
            ->add('owner')
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
            'started_at' => new DTOFieldDescription('startedAt', 'datetime'),
            'ended_at' => new DTOFieldDescription('endedAt', 'datetime'),
            'owner' => new DTOFieldDescription('owner'),
        ];
    }
}
