<?php

namespace AppBundle\Security\Voter;

use AppBundle\Admin\AbstractCharacterGroupAdmin;
use AppBundle\Entity\CharacterGroup;
use AppBundle\Entity\Organizer;
use AppBundle\Entity\User;
use AppBundle\Security\ProfileProvider;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class CharacterGroupAdminVoter extends Voter
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

        $this->rolePattern = '/^ROLE_APP_ADMIN_CHARACTER_GROUP_(FOR_(GROUP|CHARACTER)_)(.*)$/';
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

        $characterGroup = null;
        if ($subject && $subject instanceof AbstractCharacterGroupAdmin) {
            $characterGroup = $subject->getSubject();
        } elseif ($subject && $subject instanceof CharacterGroup) {
            $characterGroup = $subject;
        }

        preg_match($this->rolePattern, $attribute, $matches);

        $attribute = $matches[3];

        switch ($attribute) {
            case 'LIST':
                return $this->canList($token->getUser());
            case 'CREATE':
                return $this->canCreate($token->getUser());
            case 'VIEW':
                return $characterGroup && $this->canView($characterGroup, $token->getUser());
            case 'EDIT':
                return $characterGroup && $this->canEdit($characterGroup, $token->getUser());
            case 'DELETE':
                return $characterGroup && $this->canDelete($characterGroup, $token->getUser());
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

    protected function canView(CharacterGroup $characterGroup, User $user)
    {
        if (!$characterGroup->getId()) {
            return true;
        }

        $profile = $this->profileProvider->getActiveProfile();

        if (!$profile) {
            return false;
        }

        if ($profile instanceof Organizer && $profile->getLarp() == $characterGroup->getGroup()->getLarp()) {
            return true;
        }

        return false;
    }

    protected function canEdit(CharacterGroup $characterGroup, User $user)
    {
        $profile = $this->profileProvider->getActiveProfile();

        if (!$profile) {
            return false;
        }

        if ($profile instanceof Organizer && $profile->getLarp() == $characterGroup->getGroup()->getLarp()) {
            return true;
        }

        return false;
    }

    protected function canDelete(CharacterGroup $characterGroup, User $user)
    {
        $profile = $this->profileProvider->getActiveProfile();

        if (!$profile) {
            return false;
        }

        if ($profile instanceof Organizer && $profile->getLarp() == $characterGroup->getGroup()->getLarp()) {
            return true;
        }

        return false;
    }
}
