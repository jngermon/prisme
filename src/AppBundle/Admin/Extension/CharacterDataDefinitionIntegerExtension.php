<?php

namespace AppBundle\Admin\Extension;

use AppBundle\ENtity\CharacterDataDefinitionInteger;
use Sonata\AdminBundle\Admin\AbstractAdminExtension;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class CharacterDataDefinitionIntegerExtension extends AbstractAdminExtension
{
    public function configureFormFields(FormMapper $formMapper)
    {
        if (!$formMapper->getAdmin()->getSubject() instanceof CharacterDataDefinitionInteger) {
            return ;
        }

        $formMapper->with('bloc.options')
                ->add('default', 'integer')
                ->add('min', 'integer', [
                    'required' => false,
                ], [
                    'translation_domain' => 'CharacterDataDefinitionInteger',
                ])
                ->add('max', 'integer', [
                    'required' => false,
                ], [
                    'translation_domain' => 'CharacterDataDefinitionInteger',
                ])
            ->end()
            ;
    }

    public function configureShowFields(ShowMapper $showMapper)
    {
        if (!$showMapper->getAdmin()->getSubject() instanceof CharacterDataDefinitionInteger) {
            return ;
        }

        $showMapper->with('bloc.options')
                ->add('default', 'integer')
                ->add('min', 'integer', [
                    'translation_domain' => 'CharacterDataDefinitionInteger',
                ])
                ->add('max', 'integer', [
                    'translation_domain' => 'CharacterDataDefinitionInteger',
                ])
            ->end()
            ;
    }
}
