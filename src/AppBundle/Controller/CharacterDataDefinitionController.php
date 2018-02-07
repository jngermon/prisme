<?php

namespace AppBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController;

class CharacterDataDefinitionController extends CRUDController
{
    public function createAction()
    {
        $request = $this->getRequest();
        // the key used to lookup the template
        $templateKey = 'edit';

        $this->admin->checkAccess('create');

        $class = new \ReflectionClass($this->admin->hasActiveSubClass() ? $this->admin->getActiveSubClass() : $this->admin->getClass());

        if ($class->isAbstract()) {
            return $this->render(
                'AppBundle:CharacterDataDefinitionAdmin:select_definition.html.twig',
                array(
                    'base_template' => $this->getBaseTemplate(),
                    'admin' => $this->admin,
                    'action' => 'create',
                    'calculators' => $this->get('app.doctrine.character_data_definition.calculator')->getProcessorNames(),
                ),
                null,
                $request
            );
        }

        return parent:: createAction();
    }
}
