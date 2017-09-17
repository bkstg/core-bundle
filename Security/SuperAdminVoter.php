<?php

namespace Bkstg\CoreBundle\Security;

use Bkstg\CoreBundle\Model\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class SuperAdminVoter extends Voter
{
    /**
     * {@inheritdoc}
     */
    protected function supports($attribute, $subject)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        // Get the current user from the token.
        $user = $token->getUser();

        if ($user instanceof User && $user->hasRole('ROLE_SUPER_ADMIN')) {
            return true;
        }

        return false;
    }
}
