<?php

namespace AppBundle\Security\Voter;

use AppBundle\Admin\CharacterDataDefinitionEnumCategoryAdmin;
use AppBundle\Admin\CharacterDataDefinitionEnumCategoryItemAdmin;
use AppBundle\Entity\CharacterDataDefinitionEnumCategory;
use AppBundle\Entity\CharacterDataDefinitionEnumCategoryItem;
use AppBundle\Entity\Organizer;
use AppBundle\Entity\User;
use AppBundle\Security\ProfileProvider;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class CharacterDataDefinitionEnumCategoryAdminVoter extends Voter
{
    protected $rolePattern;

    protected $decisionManager;

    protected $profileProvider;

    public function __construct(
        AccessDecisionManagerInterface $decisionManager,
        ProfileProvider $profileProvider
    ) {
        $this->decisionManager = $decisionManager;
        $this->profileProvider = $profileProvider;

        $this->rolePattern = '/^ROLE_APP_ADMIN_CHARACTER_DATA_DEFINITION_ENUM_CATEGORY_(ITEM_)?(.*)$/';
    }

    protected function supports($attribute, $subject)
    {
        return preg_match($this->rolePattern, $attribute);
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        if ($this->decisionManager->decide($token, array('ROLE_SUPER_ADMIN'))) {
            return true;
        }

        if (!$token->getUser() instanceof User) {
            return false;
        }

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

        preg_match($this->rolePattern, $attribute, $matches);

        $attribute = $matches[2];

        switch ($attribute) {
            case 'LIST':
                return $this->canList($token->getUser());
            case 'CREATE':
                return $this->canCreate($token->getUser());
            case 'VIEW':
                return $characterDataDefinitionEnumCategory && $this->canView($characterDataDefinitionEnumCategory, $token->getUser());
            case 'EDIT':
                return $characterDataDefinitionEnumCategory && $this->canEdit($characterDataDefinitionEnumCategory, $token->getUser());
            case 'DELETE':
                return $characterDataDefinitionEnumCategory && $this->canDelete($characterDataDefinitionEnumCategory, $token->getUser());
        }

        return false;
    }


    protected function canList(User $user)
    {
        if (!$this->profileProvider->getActiveProfile()) {
            return false;
        }

        return $this->profileProvider->getActiveProfile() instanceof Organizer;
    }

    protected function canCreate(User $user)
    {
        if (!$this->profileProvider->getActiveProfile()) {
            return false;
        }

        return $this->profileProvider->getActiveProfile() instanceof Organizer;
    }

    protected function canView(CharacterDataDefinitionEnumCategory $characterDataDefinitionEnumCategory, User $user)
    {
        if (!$characterDataDefinitionEnumCategory->getId()) {
            return true;
        }

        $profile = $this->profileProvider->getActiveProfile();

        if (!$profile) {
            return false;
        }

        if ($profile instanceof Organizer && $profile->getLarp() == $characterDataDefinitionEnumCategory->getLarp()) {
            return true;
        }

        return false;
    }

    protected function canEdit(CharacterDataDefinitionEnumCategory $characterDataDefinitionEnumCategory, User $user)
    {
        $profile = $this->profileProvider->getActiveProfile();

        if (!$profile) {
            return false;
        }

        if ($profile instanceof Organizer && $profile->getLarp() == $characterDataDefinitionEnumCategory->getLarp()) {
            return true;
        }

        return false;
    }

    protected function canDelete(CharacterDataDefinitionEnumCategory $characterDataDefinitionEnumCategory, User $user)
    {
        $profile = $this->profileProvider->getActiveProfile();

        if (!$profile) {
            return false;
        }

        if ($profile instanceof Organizer && $profile->getLarp() == $characterDataDefinitionEnumCategory->getLarp()) {
            return true;
        }

        return false;
    }
}
