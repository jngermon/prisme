<?php

namespace AppBundle\Admin;

use Doctrine\ORM\EntityRepository;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class CalendarAdmin extends BaseAdmin
{
    protected $baseRouteName = 'app_admin_calendar';
    protected $baseRoutePattern = 'calendar';

    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->with('bloc.main', [
                'class'       => 'col-md-6',
                'box_class'   => 'box box-primary',
            ])
                ->add('name')
                ->add('diffDaysWithOrigin')
                ->add('months', null, [
                    'template' => 'AppBundle:Calendar:show_months.html.twig',
                ])
                ->add('nbDays', null, [
                    'template' => 'AppBundle:Calendar:show_nb_days.html.twig',
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
                ->add('name')
                ->add('diffDaysWithOrigin')
            ->end()
            ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('name')
            ->add('diffDaysWithOrigin')
            ->add('_action', null, [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'item' => [
                        'template' => 'AppBundle:Calendar:list__months_action.html.twig',
                    ],
                ]
            ])
            ;
    }
}
