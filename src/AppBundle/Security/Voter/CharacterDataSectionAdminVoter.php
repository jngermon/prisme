<?php

namespace AppBundle\Security\Voter;

use AppBundle\Admin\CharacterDataSectionAdmin;
use AppBundle\Entity\CharacterDataSection;
use AppBundle\Entity\User;

class CharacterDataSectionAdminVoter extends BaseAdminVoter
{
    protected function getRolePattern()
    {
        return '/^ROLE_APP_ADMIN_CHARACTER_DATA_SECTION_(.*)$/';
    }

    protected function voteForAction($matches, $subject, User $user)
    {
        $characterDataSection = null;
        if ($subject && $subject instanceof CharacterDataSectionAdmin) {
            $characterDataSection = $subject->getSubject();
        } elseif ($subject && $subject instanceof CharacterDataSection) {
            $characterDataSection = $subject;
        }

        $attribute = $matches[1];

        switch ($attribute) {
            case 'LIST':
                return $this->isALarpOwner($user);
            case 'CREATE':
                return $this->isALarpOwner($user);
            case 'VIEW':
                return $characterDataSection && (!$characterDataSection->getId() || $this->isTheLarpOwner($characterDataSection, $user));
            case 'EDIT':
                return $characterDataSection && $this->isTheLarpOwner($characterDataSection, $user);
            case 'DELETE':
                return $characterDataSection && $this->isTheLarpOwner($characterDataSection, $user);
        }

        return false;
    }
}
