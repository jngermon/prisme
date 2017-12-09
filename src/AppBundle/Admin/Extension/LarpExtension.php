<?php

namespace AppBundle\Admin\Extension;

use AppBundle\Security\ProfileProvider;
use Sonata\AdminBundle\Admin\AbstractAdminExtension;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;

class LarpExtension extends AbstractAdminExtension
{
    protected $profileProvider;

    public function __construct(
        ProfileProvider $profileProvider
    ) {
        $this->profileProvider = $profileProvider;
    }

    public function configureQuery(AdminInterface $admin, ProxyQueryInterface $query, $context = 'list')
    {
        $larp = null;
        $profile = $this->profileProvider->getActiveProfile();

        if ($profile) {
            $larp = $profile->getLarp();

            $query->innerJoin($query->getRootAlias().'.larp', 'larp', 'WITH', 'larp.id = :larpId')
                ->setParameter('larpId', $larp ? $larp->getId() : null)
                ;
        }

    }

    public function alterNewInstance(AdminInterface $admin, $object)
    {
        $larp = null;
        $profile = $this->profileProvider->getActiveProfile();

        if ($profile) {
            $object->setLarp($profile->getLarp());
        }
    }
}
