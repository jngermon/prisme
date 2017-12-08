<?php

namespace ExternalBundle\Admin;

use ExternalBundle\Domain\Import\Common\Status;
use ExternalBundle\Domain\Synchronizer\SynchronizerInterface;
use Sonata\AdminBundle\Admin\AbstractAdminExtension;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class SynchronizeAdminExtension extends AbstractAdminExtension
{
    protected $synchronizer;

    public function __construct(
        SynchronizerInterface $synchronizer
    ) {
        $this->synchronizer = $synchronizer;
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
        if ($object->isExternal() && $action == 'show') {
            $list['synchronize'] = [
                'template' => 'ExternalBundle:Admin:Button/synchronize_button.html.twig',
                'link_parameters' => [
                    'class' => get_class($object),
                    'ids' => [$object->getExternalId()],
                ],
            ];
        }

        return $list;
    }

    public function configureFormFields(FormMapper $formMapper)
    {
        $subject = $formMapper->getAdmin()->getSubject();

        $externalFields = $this->synchronizer->getMappings($subject);

        if ($subject->isExternal()) {
            foreach ($formMapper->getFormBuilder() as $field) {
                if ($field->getMapped() && in_array($field->getPropertyPath()->__toString(), $externalFields)) {
                    $field->setDisabled(true);
                }
            }
        }
    }
}
