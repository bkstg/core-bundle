<?php

namespace Bkstg\CoreBundle\Security;

use Bkstg\CoreBundle\Entity\Group\GroupMembershipInterface;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;

class GroupRoleHierarchyVoter extends GroupRoleVoter
{
    private $hierarchy;

    public function __construct(RoleHierarchyInterface $hierarchy)
    {
        $this->hierarchy = $hierarchy;
    }

    /**
     * Returns the roles for this membership.
     */
    public function extractRoles(GroupMembershipInterface $membership)
    {
        return $this->hierarchy->getReachableRoles(parent::extractRoles($membership));
    }
}
