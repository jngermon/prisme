<?php

namespace AppBundle\Admin\Extension;

use AppBundle\Entity\CharacterDataDefinitionBoolean;
use Sonata\AdminBundle\Admin\AbstractAdminExtension;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class CharacterDataDefinitionBooleanExtension extends AbstractAdminExtension
{
    public function configureFormFields(FormMapper $formMapper)
    {
        if (!$formMapper->getAdmin()->getSubject() instanceof CharacterDataDefinitionBoolean) {
            return ;
        }

        $formMapper->with('bloc.options')
                ->add('default', 'checkbox', [
                    'required' => false,
                ])
                ->add('true', 'text', [], [
                    'translation_domain' => 'CharacterDataDefinitionBoolean',
                ])
                ->add('false', 'text', [], [
                    'translation_domain' => 'CharacterDataDefinitionBoolean',
                ])
            ->end()
            ;
    }

    public function configureShowFields(ShowMapper $showMapper)
    {
        if (!$showMapper->getAdmin()->getSubject() instanceof CharacterDataDefinitionBoolean) {
            return ;
        }

        $showMapper->with('bloc.options')
                ->add('default', 'boolean')
                ->add('true', 'text', [
                    'translation_domain' => 'CharacterDataDefinitionBoolean',
                ])
                ->add('false', 'text', [
                    'translation_domain' => 'CharacterDataDefinitionBoolean',
                ])
            ->end()
            ;
    }
}
