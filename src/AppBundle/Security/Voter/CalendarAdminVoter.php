<?php

namespace AppBundle\Security\Voter;

use AppBundle\Admin\CalendarAdmin;
use AppBundle\Admin\CalendarMonthAdmin;
use AppBundle\Entity\Calendar;
use AppBundle\Entity\CalendarMonth;
use AppBundle\Entity\Organizer;
use AppBundle\Entity\User;
use AppBundle\Security\ProfileProvider;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class CalendarAdminVoter extends Voter
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

        $this->rolePattern = '/^ROLE_APP_ADMIN_CALENDAR_(MONTH_)?(.*)$/';
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

        $calendar = null;
        if ($subject && $subject instanceof CalendarAdmin) {
            $calendar = $subject->getSubject();
        } elseif ($subject && $subject instanceof Calendar) {
            $calendar = $subject;
        } elseif ($subject && $subject instanceof CalendarMonthAdmin) {
            $calendar = $subject->getParent()->getSubject();
        } elseif ($subject && $subject instanceof CalendarMonth) {
            $calendar = $subject->getCalendar();
        }

        preg_match($this->rolePattern, $attribute, $matches);

        $attribute = $matches[2];

        switch ($attribute) {
            case 'LIST':
                return $this->canList($token->getUser());
            case 'CREATE':
                return $this->canCreate($token->getUser());
            case 'VIEW':
                return $calendar && $this->canView($calendar, $token->getUser());
            case 'EDIT':
                return $calendar && $this->canEdit($calendar, $token->getUser());
            case 'DELETE':
                return $calendar && $this->canDelete($calendar, $token->getUser());
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

    protected function canView(Calendar $calendar, User $user)
    {
        if (!$calendar->getId()) {
            return true;
        }

        $profile = $this->profileProvider->getActiveProfile();

        if (!$profile) {
            return false;
        }

        if ($profile instanceof Organizer && $profile->getLarp() == $calendar->getLarp()) {
            return true;
        }

        return false;
    }

    protected function canEdit(Calendar $calendar, User $user)
    {
        $profile = $this->profileProvider->getActiveProfile();

        if (!$profile) {
            return false;
        }

        if ($profile instanceof Organizer && $profile->getLarp() == $calendar->getLarp()) {
            return true;
        }

        return false;
    }

    protected function canDelete(Calendar $calendar, User $user)
    {
        $profile = $this->profileProvider->getActiveProfile();

        if (!$profile) {
            return false;
        }

        if ($profile instanceof Organizer && $profile->getLarp() == $calendar->getLarp()) {
            return true;
        }

        return false;
    }
}
