<?php

namespace AppBundle\Admin;

use MMC\SonataAdminBundle\Admin\AbstractAdmin;

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

    public function getActionButtons($action, $object = null)
    {
        $list = parent::getActionButtons($action, $object);

        usort($list, function ($a, $b) {
            $pa = isset($a['priority']) ? $a['priority'] : 0;
            $pb = isset($b['priority']) ? $b['priority'] : 0;

            return $pb - $pa;
        });

        return $list;
    }
}
