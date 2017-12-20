<?php

namespace AppBundle\Admin\Model;

use Sonata\DoctrineORMAdminBundle\Model\ModelManager as BaseModelManager;

class ModelManager extends BaseModelManager
{
    public function getNewFieldDescriptionInstance($class, $name, array $options = array())
    {
        if (!isset($options['route']['name'])) {
            $options['route']['name'] = 'show'; // Override 'edit' default value
        }

        return parent::getNewFieldDescriptionInstance($class, $name, $options);
    }
}
