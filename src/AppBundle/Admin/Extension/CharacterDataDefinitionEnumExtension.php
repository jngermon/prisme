<?php

namespace AppBundle\Admin\Extension;

use AppBundle\Entity\CharacterDataDefinitionEnum;
use AppBundle\Entity\CharacterDataDefinitionEnumCategory;
use Doctrine\ORM\EntityRepository;
use Sonata\AdminBundle\Admin\AbstractAdminExtension;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class CharacterDataDefinitionEnumExtension extends AbstractAdminExtension
{
    public function configureFormFields(FormMapper $formMapper)
    {
        if (!$formMapper->getAdmin()->getSubject() instanceof CharacterDataDefinitionEnum) {
            return ;
        }

        $formMapper->with('bloc.options')
                ->add('category', 'entity', [
                    'class' => CharacterDataDefinitionEnumCategory::class,
                    'query_builder' => function (EntityRepository $er) use ($formMapper) {
                        return $er->createQueryBuilder('t')
                            ->andWhere('t.larp = :larp')
                            ->setParameter('larp', $formMapper->getAdmin()->getSubject()->getLarp())
                            ->orderBy('t.label', 'ASC')
                            ;
                    }
                ], [
                    'translation_domain' => 'CharacterDataDefinitionEnum',
                ])
            ->end()
            ;
    }

    public function configureShowFields(ShowMapper $showMapper)
    {
        if (!$showMapper->getAdmin()->getSubject() instanceof CharacterDataDefinitionEnum) {
            return ;
        }

        $showMapper->with('bloc.options')
                ->add('category', 'entity', [
                    'class' => CharacterDataDefinitionEnumCategory::class,
                    'translation_domain' => 'CharacterDataDefinitionEnum',
                ])
            ->end()
            ;
    }
}
