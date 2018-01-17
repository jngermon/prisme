<?php

namespace AppBundle\Security\Voter;

use AppBundle\Admin\AbstractCharacterGroupAdmin;
use AppBundle\Entity\CharacterGroup;
use AppBundle\Entity\User;

class CharacterGroupAdminVoter extends BaseAdminVoter
{
    protected function getRolePattern()
    {
        return '/^ROLE_APP_ADMIN_CHARACTER_GROUP_(FOR_(GROUP|CHARACTER)_)(.*)$/';
    }

    protected function voteForAction($matches, $subject, User $user)
    {
        $characterGroup = null;
        if ($subject && $subject instanceof AbstractCharacterGroupAdmin) {
            $characterGroup = $subject->getSubject();
        } elseif ($subject && $subject instanceof CharacterGroup) {
            $characterGroup = $subject;
        }

        $attribute = $matches[3];

        switch ($attribute) {
            case 'LIST':
                return $this->isAnOrganizer($user);
            case 'CREATE':
                return $this->isAnOrganizer($user);
            case 'VIEW':
                return $characterGroup && (!$characterGroup->getId() || $this->isTheOrganizer($characterGroup, $user));
            case 'EDIT':
                return $characterGroup && $this->isTheOrganizer($characterGroup, $user);
            case 'DELETE':
                return $characterGroup && $this->isTheOrganizer($characterGroup, $user);
        }

        return false;
    }
}
