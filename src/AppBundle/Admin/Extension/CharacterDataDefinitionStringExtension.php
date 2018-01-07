<?php

namespace AppBundle\Admin\Extension;

use AppBundle\ENtity\CharacterDataDefinitionString;
use Sonata\AdminBundle\Admin\AbstractAdminExtension;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class CharacterDataDefinitionStringExtension extends AbstractAdminExtension
{
    public function configureFormFields(FormMapper $formMapper)
    {
        if (!$formMapper->getAdmin()->getSubject() instanceof CharacterDataDefinitionString) {
            return ;
        }

        $formMapper->with('bloc.options')
                ->add('maxLength', 'integer', [
                ], [
                    'translation_domain' => 'CharacterDataDefinitionString',
                ])
            ->end()
            ;
    }

    public function configureShowFields(ShowMapper $showMapper)
    {
        if (!$showMapper->getAdmin()->getSubject() instanceof CharacterDataDefinitionString) {
            return ;
        }

        $showMapper->with('bloc.options')
                ->add('maxLength', 'integer', [
                    'translation_domain' => 'CharacterDataDefinitionString',
                ])
            ->end()
            ;
    }
}
