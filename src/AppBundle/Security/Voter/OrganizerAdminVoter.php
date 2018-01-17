<?php

namespace AppBundle\Security\Voter;

use AppBundle\Admin\OrganizerAdmin;
use AppBundle\Entity\Organizer;
use AppBundle\Entity\User;

class OrganizerAdminVoter extends BaseAdminVoter
{
    protected function getRolePattern()
    {
        return '/^ROLE_APP_ADMIN_ORGANIZER_(.*)$/';
    }

    protected function voteForAction($matches, $subject, User $user)
    {
        $organizer = null;
        if ($subject && $subject instanceof OrganizerAdmin) {
            $organizer = $subject->getSubject();
        } elseif ($subject && $subject instanceof Organizer) {
            $organizer = $subject;
        }

        $attribute = $matches[1];

        switch ($attribute) {
            case 'LIST':
                return $this->isAnOrganizer($user);
            case 'VIEW':
                return $organizer && (!$organizer->getId() || $this->isTheOrganizer($organizer, $user));
        }

        return false;
    }
}
