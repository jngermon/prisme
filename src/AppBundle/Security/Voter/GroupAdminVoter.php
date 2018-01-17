<?php

namespace AppBundle\Security\Voter;

use AppBundle\Admin\GroupAdmin;
use AppBundle\Entity\Group;
use AppBundle\Entity\User;

class GroupAdminVoter extends BaseAdminVoter
{
    protected function getRolePattern()
    {
        return '/^ROLE_APP_ADMIN_GROUP_(.*)$/';
    }

    protected function voteForAction($matches, $subject, User $user)
    {
        $group = null;
        if ($subject && $subject instanceof GroupAdmin) {
            $group = $subject->getSubject();
        } elseif ($subject && $subject instanceof Group) {
            $group = $subject;
        }

        $attribute = $matches[1];

        switch ($attribute) {
            case 'LIST':
                return $this->isAnOrganizer($user);
            case 'CREATE':
                return $this->isAnOrganizer($user);
            case 'VIEW':
                return $group && (!$group->getId() || $this->isTheOrganizer($group, $user));
            case 'EDIT':
                return $group && $this->isTheOrganizer($group, $user);
            case 'DELETE':
                return $group && $this->isTheOrganizer($group, $user);
        }

        return false;
    }
}
