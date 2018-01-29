<?php

namespace AppBundle\Admin;

use Greg0ire\Enum\Bridge\Symfony\Form\Type\EnumType;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class CalendarMonthAdmin extends BaseAdmin
{
    protected $parentAssociationMapping = 'calendar';

    protected $baseRouteName = 'app_admin_calendar_month';
    protected $baseRoutePattern = 'calendar_month';

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
                ->add('number')
                ->add('name')
                ->add('nbDays')
                ->add('nameForDate')
                ->add('formatDay')
            ->end()
            ->with('bloc.days', [
                'class'       => 'col-md-6',
                'box_class'   => 'box box-success',
            ])
                ->add('days', null, [
                    'show_label' => false,
                    'template' => 'AppBundle:CalendarMonthAdmin:show_days.html.twig'
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
                ->add('number', 'integer')
                ->add('name')
                ->add('nbDays')
                ->add('nameForDate')
                ->add('formatDay', null, ['help' => 'help.format_day'])
            ->end()
            ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('number')
            ->add('name')
            ->add('nbDays')
            ->add('_action', null, [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                ]
            ])
            ;
    }
}
