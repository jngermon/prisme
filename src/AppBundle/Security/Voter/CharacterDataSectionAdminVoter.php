<?php

namespace AppBundle\Security\Voter;

use AppBundle\Admin\CharacterDataSectionAdmin;
use AppBundle\Entity\CharacterDataSection;
use AppBundle\Entity\Organizer;
use AppBundle\Entity\User;
use AppBundle\Security\ProfileProvider;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class CharacterDataSectionAdminVoter extends Voter
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

        $this->rolePattern = '/^ROLE_APP_ADMIN_CHARACTER_DATA_SECTION_(.*)$/';
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

        $characterDataSection = null;
        if ($subject && $subject instanceof CharacterDataSectionAdmin) {
            $characterDataSection = $subject->getSubject();
        } elseif ($subject && $subject instanceof CharacterDataSection) {
            $characterDataSection = $subject;
        }

        preg_match($this->rolePattern, $attribute, $matches);

        $attribute = $matches[1];

        switch ($attribute) {
            case 'LIST':
                return $this->canList($token->getUser());
            case 'CREATE':
                return $this->canCreate($token->getUser());
            case 'VIEW':
                return $characterDataSection && $this->canView($characterDataSection, $token->getUser());
            case 'EDIT':
                return $characterDataSection && $this->canEdit($characterDataSection, $token->getUser());
            case 'DELETE':
                return $characterDataSection && $this->canDelete($characterDataSection, $token->getUser());
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

    protected function canView(CharacterDataSection $characterDataSection, User $user)
    {
        if (!$characterDataSection->getId()) {
            return true;
        }

        $profile = $this->profileProvider->getActiveProfile();

        if (!$profile) {
            return false;
        }

        if ($profile instanceof Organizer && $profile->getLarp() == $characterDataSection->getLarp()) {
            return true;
        }

        return false;
    }

    protected function canEdit(CharacterDataSection $characterDataSection, User $user)
    {
        $profile = $this->profileProvider->getActiveProfile();

        if (!$profile) {
            return false;
        }

        if ($profile instanceof Organizer && $profile->getLarp() == $characterDataSection->getLarp()) {
            return true;
        }

        return false;
    }

    protected function canDelete(CharacterDataSection $characterDataSection, User $user)
    {
        $profile = $this->profileProvider->getActiveProfile();

        if (!$profile) {
            return false;
        }

        if ($profile instanceof Organizer && $profile->getLarp() == $characterDataSection->getLarp()) {
            return true;
        }

        return false;
    }
}
