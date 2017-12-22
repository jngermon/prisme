<?php

namespace AppBundle\Admin;

use AppBundle\Entity\User;
use Knp\Menu\ItemInterface as MenuItemInterface;
use MMC\SonataAdminBundle\Datagrid\DTOFieldDescription;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

abstract class AbstractCharacterOrganizerAdmin extends BaseAdmin
{
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->with('bloc.membership', [
                'class'   => 'col-md-7',
                'box_class'=> 'box box-primary',
            ])
                ->add('organizer')
                ->add('character')
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
            ->with('bloc.membership', [
                'class'       => '',
                'box_class'   => 'box box-primary',
            ])
                ->add('organizer', 'text', [
                    'disabled' => true,
                ])
                ->add('character', ($this->getSubject()->getId() ? 'text' : null), [
                    'disabled' => $this->getSubject()->getId(),
                ])
            ->end()
            ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('organizer')
            ->add('character')
            ->add('character.player')
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
            'created_at' => new DTOFieldDescription('createdAt', 'datetime'),
        ];
    }
}
