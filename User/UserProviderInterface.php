<?php

namespace Bkstg\CoreBundle\User;

use Bkstg\CoreBundle\User\UserInterface;
use MidnightLuke\GroupSecurityBundle\Model\GroupInterface;

interface UserProviderInterface
{
    public function loadUserByUsername(string $username): ?UserInterface;
}
