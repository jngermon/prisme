<?php

namespace AppBundle\Security\Voter;

use AppBundle\Admin\PlayerAdmin;
use AppBundle\Entity\Player;
use AppBundle\Entity\User;

class PlayerAdminVoter extends BaseAdminVoter
{
    protected function getRolePattern()
    {
        return '/^ROLE_APP_ADMIN_PLAYER_(.*)$/';
    }

    protected function voteForAction($matches, $subject, User $user)
    {
        $player = null;
        if ($subject && $subject instanceof PlayerAdmin) {
            $player = $subject->getSubject();
        } elseif ($subject && $subject instanceof Player) {
            $player = $subject;
        }

        $attribute = $matches[1];

        switch ($attribute) {
            case 'LIST':
                return $this->isAnOrganizer($user);
            case 'CREATE':
                return $this->canCreate($user);
            case 'VIEW':
                return $player && (!$player->getId() || $this->isTheOrganizer($player, $user));
            case 'EDIT':
                return $player && $this->isTheOrganizer($player, $user);
        }

        return false;
    }

    protected function canCreate(User $user)
    {
        $profile = $this->profileProvider->getActiveProfile();
        if (!$profile) {
            return false;
        }

        if (!$profile instanceof Organizer) {
            return false;
        }

        if (!$profile->getLarp()) {
            return false;
        }

        return !$profile->getLarp()->isExternal();
    }
}
