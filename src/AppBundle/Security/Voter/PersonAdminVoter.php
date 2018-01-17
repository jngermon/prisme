<?php

namespace AppBundle\Security\Voter;

use AppBundle\Admin\PersonAdmin;
use AppBundle\Entity\Person;
use AppBundle\Entity\User;

class PersonAdminVoter extends BaseAdminVoter
{
    protected function getRolePattern()
    {
        return '/^ROLE_APP_ADMIN_PERSON_(.*)$/';
    }

    protected function voteForAction($matches, $subject, User $user)
    {
        $person = null;
        if ($subject && $subject instanceof PersonAdmin) {
            $person = $subject->getSubject();
        } elseif ($subject && $subject instanceof Person) {
            $person = $subject;
        }

        $attribute = $matches[1];

        switch ($attribute) {
            case 'LIST':
                return $this->isAnOrganizer($user);
            case 'CREATE':
                return $user->getPerson() === null;
            case 'VIEW':
                return $person && $this->canView($person, $user);
            case 'EDIT':
                return $person && $this->canEdit($person, $user);
        }

        return false;
    }

    protected function canView(Person $person, User $user)
    {
        if ($this->canEdit($person, $user)) {
            return true;
        }
        if (!$this->profileProvider->getActiveProfile()) {
            return false;
        }

        if ($this->profileProvider->getActiveProfile() instanceof Organizer) {
            return true;
        }

        return false;
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
