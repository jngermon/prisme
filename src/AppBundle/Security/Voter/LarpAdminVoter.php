<?php

namespace AppBundle\Security\Voter;

use AppBundle\Admin\LarpAdmin;
use AppBundle\Entity\Larp;
use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class LarpAdminVoter extends Voter
{
    protected $rolePattern;

    protected $decisionManager;

    public function __construct(AccessDecisionManagerInterface $decisionManager)
    {
        $this->decisionManager = $decisionManager;

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
                return true;
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
}
