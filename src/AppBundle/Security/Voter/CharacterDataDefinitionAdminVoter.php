<?php

namespace AppBundle\Security\Voter;

use AppBundle\Admin\CharacterDataDefinitionAdmin;
use AppBundle\Entity\CharacterDataDefinition;
use AppBundle\Entity\Organizer;
use AppBundle\Entity\User;
use AppBundle\Security\ProfileProvider;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class CharacterDataDefinitionAdminVoter extends Voter
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

        $this->rolePattern = '/^ROLE_APP_ADMIN_CHARACTER_DATA_DEFINITION_(.*)$/';
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

        $characterDataDefinition = null;
        if ($subject && $subject instanceof CharacterDataDefinitionAdmin) {
            $characterDataDefinition = $subject->getSubject();
        } elseif ($subject && $subject instanceof CharacterDataDefinition) {
            $characterDataDefinition = $subject;
        }

        preg_match($this->rolePattern, $attribute, $matches);

        $attribute = $matches[1];

        switch ($attribute) {
            case 'LIST':
                return $this->canList($token->getUser());
            case 'CREATE':
                return $this->canCreate($token->getUser());
            case 'VIEW':
                return $characterDataDefinition && $this->canView($characterDataDefinition, $token->getUser());
            case 'EDIT':
                return $characterDataDefinition && $this->canEdit($characterDataDefinition, $token->getUser());
            case 'DELETE':
                return $characterDataDefinition && $this->canDelete($characterDataDefinition, $token->getUser());
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

    protected function canView(CharacterDataDefinition $characterDataDefinition, User $user)
    {
        if (!$characterDataDefinition->getId()) {
            return true;
        }

        $profile = $this->profileProvider->getActiveProfile();

        if (!$profile) {
            return false;
        }

        if ($profile instanceof Organizer && $profile->getLarp() == $characterDataDefinition->getLarp()) {
            return true;
        }

        return false;
    }

    protected function canEdit(CharacterDataDefinition $characterDataDefinition, User $user)
    {
        $profile = $this->profileProvider->getActiveProfile();

        if (!$profile) {
            return false;
        }

        if ($profile instanceof Organizer && $profile->getLarp() == $characterDataDefinition->getLarp()) {
            return true;
        }

        return false;
    }

    protected function canDelete(CharacterDataDefinition $characterDataDefinition, User $user)
    {
        $profile = $this->profileProvider->getActiveProfile();

        if (!$profile) {
            return false;
        }

        if ($profile instanceof Organizer && $profile->getLarp() == $characterDataDefinition->getLarp()) {
            return true;
        }

        return false;
    }
}
