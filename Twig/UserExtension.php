<?php

namespace Bkstg\CoreBundle\Twig;

use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserExtension extends \Twig_Extension
{
    private $user_provider;
    private $cache = [];

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
        if (!isset($this->cache[$username])) {
            $this->cache[$username] = $this->user_provider->loadUserByUsername($username);
        }
        return $this->cache[$username];
    }
}
