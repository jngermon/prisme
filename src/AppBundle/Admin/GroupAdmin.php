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

class GroupAdmin extends BaseAdmin
{
    protected $baseRouteName = 'app_admin_group';
    protected $baseRoutePattern = 'groups';

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
                ->add('name')
                ->add('type', EnumType::class, [
                    'class' => \AppBundle\Entity\Enum\GroupType::class,
                    'catalogue' => 'GroupType',
                    'template' => 'MMCSonataAdminBundle:Enum:show_enum.html.twig',
                ])
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
                ->add('name')
                ->add('type', EnumType::class, [
                    'class' => \AppBundle\Entity\Enum\GroupType::class,
                    'choice_translation_domain' => 'GroupType',
                ])
            ->end()
            ;
    }

    public function getFormBuilder()
    {
        $this->formOptions['validation_groups'] = ['Default', 'Normal'];
        return parent::getFormBuilder();
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name', 'doctrine_orm_istring')
            ->add('type', null, [], EnumType::class, [
                'class' => \AppBundle\Entity\Enum\GroupType::class,
                'choice_translation_domain' => 'GroupType',
            ])
            ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('name')
            ->add('type', EnumType::class, [
                'class' => \AppBundle\Entity\Enum\GroupType::class,
                'catalogue' => 'GroupType',
                'template' => 'MMCSonataAdminBundle:Enum:list_enum.html.twig',
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
            'name' => new DTOFieldDescription('name'),
            'type' => new DTOFieldDescription('type'),
            'created_at' => new DTOFieldDescription('createdAt', 'datetime'),
        ];
    }
}
