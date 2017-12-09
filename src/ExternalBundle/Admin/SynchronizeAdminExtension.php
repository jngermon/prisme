<?php

namespace ExternalBundle\Admin;

use AppBundle\Entity\LarpRelatedInterface;
use AppBundle\Security\ProfileProvider;
use ExternalBundle\Domain\Import\Common\Status;
use ExternalBundle\Domain\Mapping\ExternalPropertiesProvider;
use Sonata\AdminBundle\Admin\AbstractAdminExtension;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class SynchronizeAdminExtension extends AbstractAdminExtension
{
    protected $externalPropertiesProvider;

    protected $profileProvider;

    public function __construct(
        ExternalPropertiesProvider $externalPropertiesProvider,
        ProfileProvider $profileProvider
    ) {
        $this->externalPropertiesProvider = $externalPropertiesProvider;
        $this->profileProvider = $profileProvider;
    }

    public function configureShowFields(ShowMapper $showMapper)
    {
        $subject = $showMapper->getAdmin()->getSubject();

        if ($subject->isExternal()) {

            $boxColor = '';
            switch ($subject->getSyncStatus()) {
                case Status::ERROR:
                    $boxColor = 'box-danger';
                    break;
                case Status::PENDING:
                    $boxColor = 'box-warning';
                    break;
                case Status::SYNCED:
                default:
                    $boxColor = 'box-success';
                    break;
            }

            $showMapper
                ->with('bloc.sync', [
                    'class'       => 'col-md-5',
                    'box_class'   => 'box '.$boxColor,
                    'translation_domain' => 'Sync',
                ])
                    ->add('syncedAt', null, [
                        'translation_domain' => 'Sync',
                    ])
                    ->add('externalId', null, [
                        'translation_domain' => 'Sync',
                    ])
                    ->add('syncStatus', null, [
                        'translation_domain' => 'Sync',
                        'class' => Status::class,
                        'catalogue' => 'Sync',
                        'template' => 'MMCSonataAdminBundle:Enum:show_enum.html.twig',
                    ])
                    ->add('syncErrors', null, [
                        'translation_domain' => 'Sync',
                    ])
                ->end()
                ;
        }
    }

    public function configureActionButtons(AdminInterface $admin, $list, $action, $object)
    {
        if ($action == 'show' && $object &&$object->isExternal()) {
            $list['synchronize'] = [
                'template' => 'ExternalBundle:Admin:Button/synchronize_button.html.twig',
                'link_parameters' => [
                    'class' => get_class($object),
                    'ids' => [$object->getExternalId()],
                ],
                'priority' => 10,
            ];
        }

        if ($action == 'list' && is_subclass_of($admin->getClass(), LarpRelatedInterface::class)) {
            $profile = $this->profileProvider->getActiveProfile();
            if ($profile) {
                $list['synchronize'] = [
                    'template' => 'ExternalBundle:Admin:Button/synchronize_button.html.twig',
                    'link_parameters' => [
                        'class' => $admin->getClass(),
                        'larp_id' => $profile->getLarp()->getId(),
                    ],
                    'priority' => 10,
                ];
            }
        }

        return $list;
    }

    public function configureFormFields(FormMapper $formMapper)
    {
        $subject = $formMapper->getAdmin()->getSubject();

        $externalFields = $this->externalPropertiesProvider->getExternalPropertiesFor($subject);

        if (is_array($externalFields) && $subject->isExternal()) {
            foreach ($formMapper->getFormBuilder() as $field) {
                if ($field->getMapped() && in_array($field->getPropertyPath()->__toString(), $externalFields)) {
                    $field->setDisabled(true);
                }
            }
        }
    }

    public function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('syncStatus', null, [
                'template' => 'ExternalBundle:Admin:CRUD/list_status.html.twig',
                'translation_domain' => 'Sync',
            ]);
    }
}
