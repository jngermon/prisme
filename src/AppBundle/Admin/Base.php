<?php

namespace AppBundle\Admin;

use MMC\SonataAdminBundle\Admin\AbstractAdmin;

class Base extends AbstractAdmin
{
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
