<?php

namespace AppBundle\Security\Voter;

use AppBundle\Admin\CharacterAdmin;
use AppBundle\Entity\Character;
use AppBundle\Entity\User;

class CharacterAdminVoter extends BaseAdminVoter
{
    protected function getRolePattern()
    {
        return '/^ROLE_APP_ADMIN_CHARACTER_(.*)$/';
    }

    protected function voteForAction($matches, $subject, User $user)
    {
        $character = null;
        if ($subject && $subject instanceof CharacterAdmin) {
            $character = $subject->getSubject();
        } elseif ($subject && $subject instanceof Character) {
            $character = $subject;
        }

        $attribute = $matches[1];

        switch ($attribute) {
            case 'LIST':
                return $this->isAnOrganizer($user);
            case 'CREATE':
                return $this->isAnOrganizer($user);
            case 'VIEW':
                return $character && (!$character->getId() || $this->isTheOrganizer($character, $user));
            case 'EDIT':
                return $character && $this->isTheOrganizer($character, $user);
            case 'DELETE':
                return $character && $this->isTheOrganizer($character, $user);
        }

        return false;
    }
}
