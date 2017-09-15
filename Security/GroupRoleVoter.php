<?php

namespace Bkstg\CoreBundle\Security;

use Bkstg\CoreBundle\Model\Group\GroupInterface;
use Bkstg\CoreBundle\Model\Group\GroupMemberInterface;
use Bkstg\CoreBundle\Model\Group\GroupMembershipInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Role\Role;

abstract class GroupRoleVoter extends Voter
{
    /**
     * {@inheritdoc}
     */
    protected function supports($attribute, $subject)
    {
        // Only evaluate group roles.
        if (!is_string($attribute)
            || 0 !== strpos($attribute, GroupMembershipInterface::GROUP_ROLE_PREFIX)) {
            return false;
        }

        // Must have a group to check against.
        if (!$subject instanceof GroupInterface) {
            return false;
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        // Get the current user from the token.
        $user = $token->getUser();

        // Vote deny on users that are not group member interface users.
        if (!$user instanceof GroupMemberInterface) {
            return false;
        }

        // Iterate over users memberships.
        foreach ($user->getMemberships() as $membership) {
            // User has an active membership.
            if ($subject->isEqualTo($membership->getGroup())
                && $membership->isActive()
                && !$membership->isExpired()) {
                // Check the roles for this membership.
                foreach ($this->extractRoles($membership) as $role) {
                    if ($role->getRole() == $attribute) {
                        return true;
                    }
                }
            }
        }

        // No match, return false.
        return false;
    }

    /**
     * Returns the roles for this membership.
     */
    public function extractRoles(GroupMembershipInterface $membership)
    {
        $roles = [];
        foreach ($membership->getRoles() as $role) {
            $roles[] = new Role($role);
        }
        return $roles;
    }
}
