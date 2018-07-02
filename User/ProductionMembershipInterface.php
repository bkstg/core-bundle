<?php

declare(strict_types=1);

/*
 * This file is part of the BkstgCoreBundle package.
 * (c) Luke Bainbridge <http://www.lukebainbridge.ca/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
