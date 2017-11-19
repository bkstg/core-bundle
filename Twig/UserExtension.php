<?php

namespace Bkstg\CoreBundle\Twig;

use Bkstg\CoreBundle\User\UserProviderInterface;

class UserExtension extends \Twig_Extension
{
    private $user_provider;

    public function __construct(UserProviderInterface $user_provider)
    {
        $this->user_provider = $user_provider;
    }

    public function getFunctions()
    {
        return [
            new \Twig_Function('get_user', [$this, 'loadUser']),
        ];
    }

    public function loadUser(string $username)
    {
        return $this->user_provider->loadUserByUsername($username);
    }
}
