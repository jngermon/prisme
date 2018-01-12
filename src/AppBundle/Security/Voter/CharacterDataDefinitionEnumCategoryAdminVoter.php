<?php

namespace AppBundle\Security\Voter;

use AppBundle\Admin\CharacterDataDefinitionEnumCategoryAdmin;
use AppBundle\Entity\CharacterDataDefinitionEnumCategory;
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

        $this->rolePattern = '/^ROLE_APP_ADMIN_CHARACTER_DATA_DEFINITION_ENUM_CATEGORY_(.*)$/';
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

        $CharacterDataDefinitionEnumCategory = null;
        if ($subject && $subject instanceof CharacterDataDefinitionEnumCategoryAdmin) {
            $CharacterDataDefinitionEnumCategory = $subject->getSubject();
        } elseif ($subject && $subject instanceof CharacterDataDefinitionEnumCategory) {
            $CharacterDataDefinitionEnumCategory = $subject;
        }

        preg_match($this->rolePattern, $attribute, $matches);

        $attribute = $matches[1];

        switch ($attribute) {
            case 'LIST':
                return $this->canList($token->getUser());
            case 'CREATE':
                return $this->canCreate($token->getUser());
            case 'VIEW':
                return $CharacterDataDefinitionEnumCategory && $this->canView($CharacterDataDefinitionEnumCategory, $token->getUser());
            case 'EDIT':
                return $CharacterDataDefinitionEnumCategory && $this->canEdit($CharacterDataDefinitionEnumCategory, $token->getUser());
            case 'DELETE':
                return $CharacterDataDefinitionEnumCategory && $this->canDelete($CharacterDataDefinitionEnumCategory, $token->getUser());
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

    protected function canView(CharacterDataDefinitionEnumCategory $CharacterDataDefinitionEnumCategory, User $user)
    {
        if (!$CharacterDataDefinitionEnumCategory->getId()) {
            return true;
        }

        $profile = $this->profileProvider->getActiveProfile();

        if (!$profile) {
            return false;
        }

        if ($profile instanceof Organizer && $profile->getLarp() == $CharacterDataDefinitionEnumCategory->getLarp()) {
            return true;
        }

        return false;
    }

    protected function canEdit(CharacterDataDefinitionEnumCategory $CharacterDataDefinitionEnumCategory, User $user)
    {
        $profile = $this->profileProvider->getActiveProfile();

        if (!$profile) {
            return false;
        }

        if ($profile instanceof Organizer && $profile->getLarp() == $CharacterDataDefinitionEnumCategory->getLarp()) {
            return true;
        }

        return false;
    }

    protected function canDelete(CharacterDataDefinitionEnumCategory $CharacterDataDefinitionEnumCategory, User $user)
    {
        $profile = $this->profileProvider->getActiveProfile();

        if (!$profile) {
            return false;
        }

        if ($profile instanceof Organizer && $profile->getLarp() == $CharacterDataDefinitionEnumCategory->getLarp()) {
            return true;
        }

        return false;
    }
}
