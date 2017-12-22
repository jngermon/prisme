<?php

namespace AppBundle\Security\Voter;

use AppBundle\Admin\AbstractCharacterOrganizerAdmin;
use AppBundle\Entity\CharacterOrganizer;
use AppBundle\Entity\Organizer;
use AppBundle\Entity\User;
use AppBundle\Security\ProfileProvider;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class CharacterOrganizerAdminVoter extends Voter
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

        $this->rolePattern = '/^ROLE_APP_ADMIN_CHARACTER_ORGANIZER_(FOR_(ORGANIZER|CHARACTER)_)(.*)$/';
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

        $characterOrganizer = null;
        if ($subject && $subject instanceof AbstractCharacterOrganizerAdmin) {
            $characterOrganizer = $subject->getSubject();
        } elseif ($subject && $subject instanceof CharacterOrganizer) {
            $characterOrganizer = $subject;
        }

        preg_match($this->rolePattern, $attribute, $matches);

        $attribute = $matches[3];

        switch ($attribute) {
            case 'LIST':
                return $this->canList($token->getUser());
            case 'CREATE':
                return $this->canCreate($token->getUser());
            case 'VIEW':
                return $characterOrganizer && $this->canView($characterOrganizer, $token->getUser());
            case 'EDIT':
                return $characterOrganizer && $this->canEdit($characterOrganizer, $token->getUser());
            case 'DELETE':
                return $characterOrganizer && $this->canDelete($characterOrganizer, $token->getUser());
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

    protected function canView(CharacterOrganizer $characterOrganizer, User $user)
    {
        if (!$characterOrganizer->getId()) {
            return true;
        }

        $profile = $this->profileProvider->getActiveProfile();

        if (!$profile) {
            return false;
        }

        if ($profile instanceof Organizer && $profile->getLarp() == $characterOrganizer->getOrganizer()->getLarp()) {
            return true;
        }

        return false;
    }

    protected function canEdit(CharacterOrganizer $characterOrganizer, User $user)
    {
        $profile = $this->profileProvider->getActiveProfile();

        if (!$profile) {
            return false;
        }

        if ($profile instanceof Organizer && $profile->getLarp() == $characterOrganizer->getOrganizer()->getLarp()) {
            return true;
        }

        return false;
    }

    protected function canDelete(CharacterOrganizer $characterOrganizer, User $user)
    {
        $profile = $this->profileProvider->getActiveProfile();

        if (!$profile) {
            return false;
        }

        if ($profile instanceof Organizer && $profile->getLarp() == $characterOrganizer->getOrganizer()->getLarp()) {
            return true;
        }

        return false;
    }
}
