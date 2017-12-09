<?php

namespace AppBundle\Security\Voter;

use AppBundle\Admin\PersonAdmin;
use AppBundle\Entity\Organizer;
use AppBundle\Entity\Person;
use AppBundle\Entity\User;
use AppBundle\Security\ProfileProvider;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class PersonAdminVoter extends Voter
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

        $this->rolePattern = '/^ROLE_APP_ADMIN_PERSON_(.*)$/';
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

        $person = null;
        if ($subject && $subject instanceof PersonAdmin) {
            $person = $subject->getSubject();
        } elseif ($subject && $subject instanceof Person) {
            $person = $subject;
        }

        preg_match($this->rolePattern, $attribute, $matches);

        $attribute = $matches[1];

        switch ($attribute) {
            case 'LIST':
                return $this->canList($token->getUser());
            case 'CREATE':
                return $this->canCreate($token->getUser());
            case 'VIEW':
                return $person && $this->canView($person, $token->getUser());
            case 'EDIT':
                return $person && $this->canEdit($person, $token->getUser());
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
        return $user->getPerson() === null;
    }

    protected function canView(Person $person, User $user)
    {
        if (!$this->profileProvider->getActiveProfile()) {
            return false;
        }

        if ($this->profileProvider->getActiveProfile() instanceof Organizer) {
            return true;
        }

        return $this->canEdit($person, $user);
    }

    protected function canEdit(Person $person, User $user)
    {
        if (!$person->getId()) {
            return true;
        }

        if (!$person->getUser()) {
            return false;
        }

        return $person->getUser() == $user;
    }
}
