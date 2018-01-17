<?php

namespace AppBundle\Security\Voter;

use AppBundle\Admin\LarpAdmin;
use AppBundle\Entity\Larp;
use AppBundle\Entity\User;

class LarpAdminVoter extends BaseAdminVoter
{
    protected function getRolePattern()
    {
        return '/^ROLE_APP_ADMIN_LARP_(.*)$/';
    }

    protected function voteForAction($matches, $subject, User $user)
    {
        $larp = null;
        if ($subject && $subject instanceof LarpAdmin) {
            $larp = $subject->getSubject();
        } elseif ($subject && $subject instanceof Larp) {
            $larp = $subject;
        }

        $attribute = $matches[1];

        switch ($attribute) {
            case 'LIST':
            case 'VIEW':
            case 'EXPORT_PARAMETERS':
                return true;
            case 'IMPORT_PARAMETERS':
                return $larp && $this->canImportParameters($larp, $user);
            case 'EDIT':
                return $larp && $this->canEdit($larp, $user);
        }

        return false;
    }

    protected function canEdit(Larp $larp, User $user)
    {
        if (!$larp->getOwner()) {
            return false;
        }

        if (!$larp->getOwner()->getUser()) {
            return false;
        }

        return $larp->getOwner()->getUser() == $user;
    }

    protected function canImportParameters(Larp $larp, User $user)
    {
        if (!$larp->getOwner()) {
            return false;
        }

        if (!$larp->getOwner()->getUser()) {
            return false;
        }

        return $larp->getOwner()->getUser() == $user;
    }
}
