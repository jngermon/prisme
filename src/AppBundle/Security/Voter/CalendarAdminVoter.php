<?php

namespace AppBundle\Security\Voter;

use AppBundle\Admin\CalendarAdmin;
use AppBundle\Admin\CalendarMonthAdmin;
use AppBundle\Entity\Calendar;
use AppBundle\Entity\CalendarMonth;
use AppBundle\Entity\User;

class CalendarAdminVoter extends BaseAdminVoter
{
    protected function getRolePattern()
    {
        return '/^ROLE_APP_ADMIN_CALENDAR_(MONTH_)?(.*)$/';
    }

    protected function voteForAction($matches, $subject, User $user)
    {
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

        $attribute = $matches[2];

        switch ($attribute) {
            case 'LIST':
                return $this->isALarpOwner($user);
            case 'CREATE':
                return $this->isALarpOwner($user);
            case 'VIEW':
                return $calendar && $this->isTheLarpOwner($calendar, $user);
            case 'EDIT':
                return $calendar && $this->isTheLarpOwner($calendar, $user);
            case 'DELETE':
                return $calendar && $this->isTheLarpOwner($calendar, $user);
        }

        return false;
    }
}
