<?php

namespace AppBundle\Admin;

use Doctrine\ORM\EntityRepository;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class CharacterDataDefinitionAdmin extends BaseAdmin
{
    protected $baseRouteName = 'app_admin_character_data_definition';
    protected $baseRoutePattern = 'character_data_definition';

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
                ->add('section')
                ->add('position')
                ->add('name')
                ->add('type', 'trans', [
                    'catalogue' => 'CharacterDataDefinition',
                ])
                ->add('label')
                ->add('required')
            ->end()
            ->with('bloc.options', [
                    'class'       => 'col-md-6',
                    'box_class'   => 'box box-primary',
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
                ->add('section', null, [
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('s')
                            ->andWhere('s.larp = :larp')
                            ->setParameter('larp', $this->getSubject()->getLarp())
                            ->orderBy('s.label', 'ASC')
                            ;
                    },
                ])
                ->add('position')
                ->add('name')
                ->add('label')
                ->add('required')
            ->end()
            ->with('bloc.options', [
                    'class'       => 'col-md-6',
                    'box_class'   => 'box box-primary',
                ])
            ->end()
            ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('section')
            ->add('position')
            ->add('name')
            ->add('type', 'trans')
            ->add('label')
            ->add('_action', null, [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                ]
            ])
            ;
    }
}
