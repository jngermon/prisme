<?php

namespace AppBundle\Security\Voter;

use AppBundle\Admin\CharacterDataDefinitionAdmin;
use AppBundle\Entity\CharacterDataDefinition;
use AppBundle\Entity\User;

class CharacterDataDefinitionAdminVoter extends BaseAdminVoter
{
    protected function getRolePattern()
    {
        return '/^ROLE_APP_ADMIN_CHARACTER_DATA_DEFINITION_(.*)$/';
    }

    protected function voteForAction($matches, $subject, User $user)
    {
        $characterDataDefinition = null;
        if ($subject && $subject instanceof CharacterDataDefinitionAdmin) {
            $characterDataDefinition = $subject->getSubject();
        } elseif ($subject && $subject instanceof CharacterDataDefinition) {
            $characterDataDefinition = $subject;
        }

        $attribute = $matches[1];

        switch ($attribute) {
            case 'LIST':
                return $this->isALarpOwner($user);
            case 'CREATE':
                return $this->isALarpOwner($user);
            case 'VIEW':
                return $characterDataDefinition && (!$characterDataDefinition->getId() || $this->isTheLarpOwner($characterDataDefinition, $user));
            case 'EDIT':
                return $characterDataDefinition && $this->isTheLarpOwner($characterDataDefinition, $user);
            case 'DELETE':
                return $characterDataDefinition && $this->isTheLarpOwner($characterDataDefinition, $user);
        }

        return false;
    }
}
