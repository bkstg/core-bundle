<?php

namespace Bkstg\CoreBundle\User;

use Bkstg\CoreBundle\User\UserProviderInterface;

class MockUserProvider implements UserProviderInterface
{
    public function loadUserByUsername(string $username)
    {
        return null;
    }
}
