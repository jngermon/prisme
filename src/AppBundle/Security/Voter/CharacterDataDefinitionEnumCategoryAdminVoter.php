<?php

namespace AppBundle\Security\Voter;

use AppBundle\Admin\CharacterDataDefinitionEnumCategoryAdmin;
use AppBundle\Admin\CharacterDataDefinitionEnumCategoryItemAdmin;
use AppBundle\Entity\CharacterDataDefinitionEnumCategory;
use AppBundle\Entity\CharacterDataDefinitionEnumCategoryItem;
use AppBundle\Entity\User;

class CharacterDataDefinitionEnumCategoryAdminVoter extends BaseAdminVoter
{
    protected function getRolePattern()
    {
        return '/^ROLE_APP_ADMIN_CHARACTER_DATA_DEFINITION_ENUM_CATEGORY_(ITEM_)?(.*)$/';
    }

    protected function voteForAction($matches, $subject, User $user)
    {
        $characterDataDefinitionEnumCategory = null;
        if ($subject && $subject instanceof CharacterDataDefinitionEnumCategoryAdmin) {
            $characterDataDefinitionEnumCategory = $subject->getSubject();
        } elseif ($subject && $subject instanceof CharacterDataDefinitionEnumCategory) {
            $characterDataDefinitionEnumCategory = $subject;
        } elseif ($subject && $subject instanceof CharacterDataDefinitionEnumCategoryItemAdmin) {
            $characterDataDefinitionEnumCategory = $subject->getParent()->getSubject();
        } elseif ($subject && $subject instanceof CharacterDataDefinitionEnumCategoryItem) {
            $characterDataDefinitionEnumCategory = $subject->getCategory();
        }

        $attribute = $matches[2];

        switch ($attribute) {
            case 'LIST':
                return $this->isALarpOwner($user) || $this->isAnOrganizer($user);
            case 'CREATE':
                return $this->isALarpOwner($user);
            case 'VIEW':
                return $characterDataDefinitionEnumCategory && (!$characterDataDefinitionEnumCategory->getId() || $this->isTheLarpOwner($characterDataDefinitionEnumCategory, $user));
            case 'EDIT':
                return $characterDataDefinitionEnumCategory && $this->isTheLarpOwner($characterDataDefinitionEnumCategory, $user);
            case 'DELETE':
                return $characterDataDefinitionEnumCategory && $this->isTheLarpOwner($characterDataDefinitionEnumCategory, $user);
        }

        return false;
    }
}
