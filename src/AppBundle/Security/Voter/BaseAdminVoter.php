<?php

namespace AppBundle\Security\Voter;

use AppBundle\Entity\LarpRelatedInterface;
use AppBundle\Entity\Organizer;
use AppBundle\Entity\User;
use AppBundle\Security\ProfileProvider;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

abstract class BaseAdminVoter extends Voter
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
    }

    abstract protected function getRolePattern();

    abstract protected function voteForAction($matches, $subject, User $user);

    protected function supports($attribute, $subject)
    {
        return preg_match($this->getRolePattern(), $attribute);
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        if ($this->decisionManager->decide($token, array('ROLE_SUPER_ADMIN'))) {
            return true;
        }

        if (!$token->getUser() instanceof User) {
            return false;
        }

        preg_match($this->getRolePattern(), $attribute, $matches);

        return $this->voteForAction($matches, $subject, $token->getUser());
    }

    protected function isALarpOwner(User $user)
    {
        if (!$this->profileProvider->getActiveProfile()) {
            return false;
        }

        if (!$this->profileProvider->getActiveProfile()->getLarp()) {
            return false;
        }

        if (!$this->profileProvider->getActiveProfile()->getLarp()->getOwner()) {
            return false;
        }

        return $this->profileProvider->getActiveProfile()->getLarp()->getOwner()->getUser() == $user;
    }

    protected function isTheLarpOwner(LarpRelatedInterface $larpRelated, User $user)
    {
        $larp = $larpRelated->getLarp();

        if (!$larp) {
            return false;
        }

        if (!$larp->getOwner()) {
            return false;
        }

        if (!$larp->getOwner()->getUser()) {
            return false;
        }

        return $larp->getOwner()->getUser() == $user;
    }

    protected function isAnOrganizer(User $user)
    {
        if (!$this->profileProvider->getActiveProfile()) {
            return false;
        }

        return $this->profileProvider->getActiveProfile() instanceof Organizer;
    }

    protected function isTheOrganizer(LarpRelatedInterface $larpRelated, User $user)
    {
        $profile = $this->profileProvider->getActiveProfile();

        if (!$profile) {
            return false;
        }

        if ($profile instanceof Organizer && $profile->getLarp() == $larpRelated->getLarp()) {
            return true;
        }

        return false;
    }
}
