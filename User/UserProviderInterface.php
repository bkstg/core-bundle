<?php

namespace Bkstg\CoreBundle\User;

use MidnightLuke\GroupSecurityBundle\Model\GroupInterface;

interface UserProviderInterface
{
    public function loadUserByUsername(string $username);
}
