<?php

namespace AppBundle\Admin;

use Doctrine\ORM\EntityRepository;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;

class CalendarAdmin extends BaseAdmin
{
    protected $baseRouteName = 'app_admin_calendar';
    protected $baseRoutePattern = 'calendar';

    protected $datagridValues = array(
        '_page' => 1,
        '_sort_order' => 'ASC',
        '_sort_by' => 'name',
    );

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
                    'template' => 'AppBundle:CalendarAdmin:show_months.html.twig',
                ])
                ->add('nbDays', null, [
                    'template' => 'AppBundle:CalendarAdmin:show_nb_days.html.twig',
                ])
                ->add('formatGlobal')
                ->add('formatYear')
                ->add('formatDay')
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
                ->add('formatGlobal', 'text', ['help' => 'help.format_global'])
                ->add('formatYear', 'text', ['help' => 'help.format_year'])
                ->add('formatDay', 'text', ['help' => 'help.format_day'])
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
                        'template' => 'AppBundle:CalendarAdmin:list__months_action.html.twig',
                    ],
                ]
            ])
            ;
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->add('convertor', 'convertor');
        $collection->add('infos', 'infos');
    }

    protected function getAccess()
    {
        return array_merge(parent::getAccess(), [
            'convertor' => 'CONVERTOR',
        ]);
    }

    public function configureActionButtons($action, $object = null)
    {

        $list = [];
        if ($action == 'list'
            && $this->hasAccess('convertor')
            && $this->hasRoute('convertor')) {
            $list['export_parameters'] = [
                'template' => 'AppBundle:CalendarAdmin:list__convertor_action.html.twig',
            ];
        }

        return array_merge($list, parent::configureActionButtons($action, $object));
    }
}
