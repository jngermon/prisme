<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use MMC\FosUserBundle\Entity\User as BaseUser;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    public function __construct()
    {
        parent::__construct();
        // your own logic
    }
}
