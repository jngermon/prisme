<?php

namespace AppBundle\Security\Voter;

use AppBundle\Admin\PlayerAdmin;
use AppBundle\Entity\Organizer;
use AppBundle\Entity\Player;
use AppBundle\Entity\User;
use AppBundle\Security\ProfileProvider;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class PlayerAdminVoter extends Voter
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

        $this->rolePattern = '/^ROLE_APP_ADMIN_PLAYER_(.*)$/';
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

        $player = null;
        if ($subject && $subject instanceof PlayerAdmin) {
            $player = $subject->getSubject();
        } elseif ($subject && $subject instanceof Player) {
            $player = $subject;
        }

        preg_match($this->rolePattern, $attribute, $matches);

        $attribute = $matches[1];

        switch ($attribute) {
            case 'LIST':
                return $this->canList($token->getUser());
            case 'CREATE':
                return $this->canCreate($token->getUser());
            case 'VIEW':
                return $player && $this->canView($player, $token->getUser());
            case 'EDIT':
                return $player && $this->canEdit($player, $token->getUser());
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

    protected function canView(Player $player, User $user)
    {
        if (!$player->getId()) {
            return true;
        }

        $profile = $this->profileProvider->getActiveProfile();

        if (!$profile) {
            return false;
        }

        if ($profile instanceof Organizer && $profile->getLarp() == $player->getLarp()) {
            return true;
        }

        if ($profile instanceof Player && $profile == $player) {
            return true;
        }

        return false;
    }

    protected function canEdit(Player $player, User $user)
    {
        $profile = $this->profileProvider->getActiveProfile();

        if (!$profile) {
            return false;
        }

        if ($profile instanceof Organizer && $profile->getLarp() == $player->getLarp()) {
            return true;
        }

        return false;
    }
}
