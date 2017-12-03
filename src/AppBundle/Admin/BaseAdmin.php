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
}
