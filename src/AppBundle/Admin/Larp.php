<?php

namespace AppBundle\Admin;

use MMC\SonataAdminBundle\Datagrid\DTOFieldDescription;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class Larp extends Base
{
    protected $baseRouteName = 'app_admin_larp';
    protected $baseRoutePattern = 'larps';

    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('name', 'text')
            ->add('startedAt')
            ->add('endedAt')
            ;
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name', 'text')
            ->add('startedAt', 'sonata_type_datetime_picker')
            ->add('endedAt', 'sonata_type_datetime_picker')
            ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('name');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name')
            ->add('startedAt')
            ->add('endedAt')
            ;
    }

    public function getExportFields()
    {
        return [
            'name' => new DTOFieldDescription('name'),
            'started_at' => new DTOFieldDescription('startedAt', 'datetime'),
            'ended_at' => new DTOFieldDescription('endedAt', 'datetime'),
        ];
    }
}
