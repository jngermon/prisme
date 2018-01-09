<?php

namespace AppBundle\Admin;

use AppBundle\Domain\CharacterDataDefinition\Form\FormFactory;
use AppBundle\Entity\User;
use Greg0ire\Enum\Bridge\Symfony\Form\Type\EnumType;
use Knp\Menu\ItemInterface as MenuItemInterface;
use MMC\SonataAdminBundle\Datagrid\DTOFieldDescription;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class CharacterAdmin extends BaseAdmin
{
    protected $baseRouteName = 'app_admin_character';
    protected $baseRoutePattern = 'characters';
    protected $formFactory;

    public function setFormFactory(FormFactory $formFactory)
    {
        $this->formFactory = $formFactory;
    }

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
                ->add('player')
                ->add('characterOrganizers', null, [
                    'admin_code' => 'app.admin.character_organizer_for_character',
                    'associated_property' => 'organizer',
                    'template' => 'AppBundle:CharacterAdmin:show_organizers.html.twig',
                ])
                ->add('name')
                ->add('title')
            ->end()
            ->with('bloc.group', [
                'class'       => 'col-md-5',
                'box_class'   => 'box box-primary',
            ])
                ->add('affiliations', null, [
                    'show_label' => false,
                    'template' => 'AppBundle:CharacterAdmin:show_affiliations.html.twig',
                ])
            ->end()
            ->with('bloc.skill', [
                'class'       => 'col-md-5',
                'box_class'   => 'box box-primary',
            ])
                ->add('characterSkills', null, [
                    'associated_property' => 'skill',
                    'label' => false,
                    'template' => 'AppBundle:CharacterAdmin:show_skills.html.twig',
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

        $sections = $this->getSubject()->getLarp()->getCharacterDataSections();

        foreach ($sections as $section) {
            $showMapper
                ->with('bloc.section.'.$section->getId(), [
                    'class' => 'col-md-'.$section->getSize(),
                    'box_class'   => 'box box-primary',
                    'name' => $section->getLabel(),
                ])
                ;

            foreach ($section->getCharacterDataDefinitions() as $definition) {
                $showMapper
                    ->add('definition_'.$definition->getName(), null, [
                        'label' => $definition->getLabel(),
                        'template' => 'AppBundle:CharacterAdmin:show_data.html.twig',
                        'definition' => $definition,
                    ]);

            }

            $showMapper->end();
        }
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('bloc.identity', [
                'class'       => '',
                'box_class'   => 'box box-primary',
            ])
                ->add('player', null, [
                    'required' => false,
                ])
                ->add('name')
                ->add('title')
            ->end()
            ;

        if (!$this->formFactory) {
            return ;
        }

        $sections = $this->getSubject()->getLarp()->getCharacterDataSections();

        foreach ($sections as $section) {
            $formMapper
                ->with('bloc.section.'.$section->getId(), [
                    'class' => 'col-md-'.$section->getSize(),
                    'box_class'   => 'box box-primary',
                    'name' => $section->getLabel(),
                ])
                ;

            foreach ($section->getCharacterDataDefinitions() as $definition) {
                $form = $this->formFactory->create($definition);
                if ($form) {
                    $formMapper->add($form);
                }
            }

            $formMapper->end();
        }
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name', 'doctrine_orm_istring')
            ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('name')
            ->add('title')
            ->add('player')
            ->add('_action', null, [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'character_group' => [
                        'template' => 'AppBundle:CharacterAdmin:list__character_group_action.html.twig',
                    ],
                ]
            ])
            ;
    }

    public function getExportFields()
    {
        return [
            'name' => new DTOFieldDescription('name'),
            'title' => new DTOFieldDescription('title'),
            'created_at' => new DTOFieldDescription('createdAt', 'datetime'),
        ];
    }

    protected function configureSideMenu(MenuItemInterface $menu, $action, AdminInterface $childAdmin = null)
    {
        if (!$childAdmin && !in_array($action, ['edit', 'show'])) {
            return;
        }

        $admin = $this->isChild() ? $this->getParent() : $this;
        $id = $admin->getRequest()->get('id');

        if ($childAdmin) {
            $menu->addChild('link_to_character', [
                'uri' => $admin->generateUrl('show', ['id' => $id])
            ]);
        }
    }
}
