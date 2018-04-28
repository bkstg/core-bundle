<?php

namespace Bkstg\CoreBundle\User;

use MidnightLuke\GroupSecurityBundle\Model\GroupMembershipInterface;

interface ProductionMembershipInterface extends GroupMembershipInterface
{
    /**
     * Returns the production roles for this user given the production.
     *
     * @return ProductionRoleInterface[] This users roles in the production.
     */
    public function getProductionRoles();
}
