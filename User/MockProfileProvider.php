<?php

namespace Bkstg\CoreBundle\User;

use Bkstg\CoreBundle\User\ProfileProviderInterface;

class MockProfileProvider implements ProfileProviderInterface
{
    public function loadProfileByUsername(string $username)
    {
        return null;
    }
}
