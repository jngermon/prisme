<?php

namespace AppBundle\Security\Voter;

use AppBundle\Admin\LarpAdmin;
use AppBundle\Entity\Larp;
use AppBundle\Entity\Organizer;
use AppBundle\Entity\User;
use AppBundle\Security\ProfileProvider;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class LarpAdminVoter extends Voter
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

        $this->rolePattern = '/^ROLE_APP_ADMIN_LARP_(.*)$/';
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

        $larp = null;
        if ($subject && $subject instanceof LarpAdmin) {
            $larp = $subject->getSubject();
        } elseif ($subject && $subject instanceof Larp) {
            $larp = $subject;
        }

        preg_match($this->rolePattern, $attribute, $matches);

        $attribute = $matches[1];

        switch ($attribute) {
            case 'LIST':
            case 'VIEW':
            case 'EXPORT_PARAMETERS':
                return true;
            case 'IMPORT_PARAMETERS':
                return $larp && $this->canImportParameters($larp, $token->getUser());
            case 'EDIT':
                return $larp && $this->canEdit($larp, $token->getUser());
        }

        return false;
    }

    protected function canEdit(Larp $larp, User $user)
    {
        if (!$larp->getOwner()) {
            return false;
        }

        if (!$larp->getOwner()->getUser()) {
            return false;
        }

        return $larp->getOwner()->getUser() == $user;
    }

    protected function canImportParameters(Larp $larp, User $user)
    {
        if (!$larp->getId()) {
            return false;
        }

        $profile = $this->profileProvider->getActiveProfile();

        if (!$profile) {
            return false;
        }

        if ($profile instanceof Organizer && $profile->getLarp() == $larp) {
            return true;
        }

        return false;
    }
}
