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
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class PersonAdmin extends BaseAdmin
{
    protected $baseRouteName = 'app_admin_person';
    protected $baseRoutePattern = 'persons';

    protected $tokenStorage;

    public function setTokenStorage(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->with('bloc.identity', [
                'class'       => 'col-md-7',
                'box_class'   => 'box box-primary',
            ])
                ->add('firstname')
                ->add('lastname')
                ->add('phone')
                ->add('birthDate')
                ->add('gender', EnumType::class, [
                    'class' => \AppBundle\Entity\Enum\Gender::class,
                    'catalogue' => 'Gender',
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
                ->add('firstname')
                ->add('lastname')
                ->add('phone')
                ->add('birthDate', 'sonata_type_date_picker', [
                    'format' => 'dd/MM/yyyy',
                ])
                ->add('gender', EnumType::class, [
                    'class' => \AppBundle\Entity\Enum\Gender::class,
                    'choice_translation_domain' => 'Gender',
                ])
            ->end()
            ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('firstname', 'doctrine_orm_istring')
            ->add('lastname', 'doctrine_orm_istring')
            ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('firstname')
            ->add('lastname')
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
            'firstname' => new DTOFieldDescription('firstname'),
            'lastname' => new DTOFieldDescription('lastname'),
            'created_at' => new DTOFieldDescription('createdAt', 'datetime'),
        ];
    }

    public function prePersist($person)
    {
        $user = $this->tokenStorage->getToken()->getUser();
        if (!$person->getUser() && $user instanceof User) {
            $person->setUser($user);
        }
    }

    public function getTemplate($name)
    {
        switch ($name) {
            case 'edit':
                return 'AppBundle:PersonAdmin:edit.html.twig';
                break;

            default:
                return parent::getTemplate($name);
                break;
        }
    }
}
