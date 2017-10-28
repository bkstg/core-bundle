<?php

namespace Bkstg\CoreBundle\User;

interface ProfileProviderInterface
{
    public function loadProfileByUsername(string $username);
}
