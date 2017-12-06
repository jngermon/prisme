<?php

namespace AppBundle\Admin;

use ExternalBundle\Domain\Import\Common\Status;
use Greg0ire\Enum\Bridge\Symfony\Form\Type\EnumType;
use MMC\SonataAdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Show\ShowMapper;

class BaseAdmin extends AbstractAdmin
{
    protected $searchResultActions = ['show'];

    public function getExportFormats()
    {
        if (!count($this->getExportFields())) {
            return [];
        }

        return ['csv'];
    }

    public function getBatchActions()
    {
        return [];
    }

    public function getExportFields()
    {
        return [];
    }

    protected function configureShowFieldsSynchronizable(ShowMapper $showMapper)
    {
        if ($this->getSubject()->isExternal()) {

            $boxColor = '';
            switch ($this->getSubject()->getSyncStatus()) {
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
                    ->add('syncStatus', EnumType::class, [
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
}
