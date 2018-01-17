<?php

namespace AppBundle\Security\Voter;

use AppBundle\Admin\AbstractCharacterOrganizerAdmin;
use AppBundle\Entity\CharacterOrganizer;
use AppBundle\Entity\User;

class CharacterOrganizerAdminVoter extends BaseAdminVoter
{
    protected function getRolePattern()
    {
        return '/^ROLE_APP_ADMIN_CHARACTER_ORGANIZER_(FOR_(ORGANIZER|CHARACTER)_)(.*)$/';
    }

    protected function voteForAction($matches, $subject, User $user)
    {
        $characterOrganizer = null;
        if ($subject && $subject instanceof AbstractCharacterOrganizerAdmin) {
            $characterOrganizer = $subject->getSubject();
        } elseif ($subject && $subject instanceof CharacterOrganizer) {
            $characterOrganizer = $subject;
        }

        $attribute = $matches[3];

        switch ($attribute) {
            case 'LIST':
                return $this->isAnOrganizer($user);
            case 'CREATE':
                return $this->isAnOrganizer($user);
            case 'VIEW':
                return $characterOrganizer && (!$characterOrganizer->getId() || $this->isTheOrganizer($characterOrganizer, $user));
            case 'EDIT':
                return $characterOrganizer && $this->isTheOrganizer($characterOrganizer, $user);
            case 'DELETE':
                return $characterOrganizer && $this->isTheOrganizer($characterOrganizer, $user);
        }

        return false;
    }
}
