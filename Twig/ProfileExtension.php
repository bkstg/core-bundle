<?php

namespace Bkstg\CoreBundle\Twig;

use Bkstg\CoreBundle\User\ProfileProviderInterface;

class ProfileExtension extends \Twig_Extension
{
    private $profile_provider;

    public function __construct(ProfileProviderInterface $profile_provider)
    {
        $this->profile_provider = $profile_provider;
    }

    public function getFunctions()
    {
        return [
            new \Twig_Function('profile', [$this, 'loadProfile']),
        ];
    }

    public function loadProfile(string $username)
    {
        return $this->profile_provider->loadProfileByUsername($username);
    }
}
