<?php

namespace AppBundle\Admin\Extension;

use AppBundle\Domain\CharacterDataDefinition\Calculator\Calculator;
use AppBundle\Entity\CharacterDataDefinitionCalculator;
use Doctrine\Common\Inflector\Inflector;
use Sonata\AdminBundle\Admin\AbstractAdminExtension;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class CharacterDataDefinitionCalculatorExtension extends AbstractAdminExtension
{
    protected $calculator;

    public function __construct(
        Calculator $calculator
    ) {
        $this->calculator = $calculator;
    }

    public function configureFormFields(FormMapper $formMapper)
    {
        $definition = $formMapper->getAdmin()->getSubject();
        if (!$definition instanceof CharacterDataDefinitionCalculator) {
            return ;
        }

        $formMapper->with('bloc.options')
            ->add('processor', 'hidden')
            ->remove('required')
            ;

        $mapping = $this->calculator->getProcessorMapping($definition->getProcessor());

        foreach ($mapping as $field) {
            $formMapper->add('mapping_'.$field, 'text', [
                    'property_path' => 'mapping['.$field.']',
                ], [
                    'translation_domain' => 'CharacterDataDefinitionCalculator'.Inflector::classify($definition->getProcessor()),
                ]);
        }

        $formMapper->end();
    }

    public function configureShowFields(ShowMapper $showMapper)
    {
        $definition = $showMapper->getAdmin()->getSubject();
        if (!$definition instanceof CharacterDataDefinitionCalculator) {
            return ;
        }

        $showMapper->with('bloc.options')
            ->remove('required')
        ;

        $mapping = $this->calculator->getProcessorMapping($definition->getProcessor());

        foreach ($mapping as $field) {
            $showMapper->add('mapping.'.$field, 'text', [
                    'translation_domain' => 'CharacterDataDefinitionCalculator'.Inflector::classify($definition->getProcessor()),
                ]);
        }

        $showMapper->end()
            ;
    }

    public function alterNewInstance(AdminInterface $admin, $object)
    {
        if (!$object instanceof CharacterDataDefinitionCalculator) {
            return ;
        }

        $processor = $admin->getRequest()->query->get('calculator');

        if (!$processor) {
            throw new \Exception('You must specified calculator name');
        }

        if (!in_array($processor, $this->calculator->getProcessorNames())) {
            throw new \Exception('This processor name is not defined');
        }

        $object->setProcessor($processor);
    }
}
