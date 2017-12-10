<?php

namespace ExternalBundle\Security\Voter;

use AppBundle\Entity\User;
use ExternalBundle\Admin\SynchronizationAdmin;
use ExternalBundle\Entity\Synchronization;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class SynchronizationAdminVoter extends Voter
{
    protected $rolePattern;

    protected $decisionManager;

    public function __construct(
        AccessDecisionManagerInterface $decisionManager
    ) {
        $this->decisionManager = $decisionManager;

        $this->rolePattern = '/^ROLE_APP_ADMIN_SYNCHRONIZATION_(.*)$/';
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

        $synchronization = null;
        if ($subject && $subject instanceof SynchronizationAdmin) {
            $synchronization = $subject->getSubject();
        } elseif ($subject && $subject instanceof Synchronization) {
            $synchronization = $subject;
        }

        preg_match($this->rolePattern, $attribute, $matches);

        $attribute = $matches[1];

        switch ($attribute) {
            case 'LIST':
                return $this->canList($token->getUser());
            case 'CREATE':
                return $this->canCreate($token->getUser());
            case 'VIEW':
                return $synchronization && $this->canView($synchronization, $token->getUser());
            case 'EDIT':
                return $synchronization && $this->canEdit($synchronization, $token->getUser());
        }

        return false;
    }


    protected function canList(User $user)
    {
        return true;
    }

    protected function canCreate(User $user)
    {
        return false;
    }

    protected function canView(Synchronization $synchronization, User $user)
    {
        return true;
    }

    protected function canEdit(Synchronization $synchronization, User $user)
    {
        return false;
    }
}
