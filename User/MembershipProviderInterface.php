<?php

namespace Bkstg\CoreBundle\User;

use MidnightLuke\GroupSecurityBundle\Model\GroupInterface;

interface MembershipProviderInterface
{
    public function loadMembershipsByGroup(GroupInterface $group);
}
