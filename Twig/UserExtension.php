<?php

namespace Bkstg\CoreBundle\Twig;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserExtension extends \Twig_Extension
{
    private $user_provider;
    private $cache = [];

    /**
     * Build a new user extension.
     *
     * @param UserProviderInterface $user_provider The user provider service.
     */
    public function __construct(UserProviderInterface $user_provider)
    {
        $this->user_provider = $user_provider;
    }

    /**
     * Return set of twig functions.
     *
     * @return array
     */
    public function getFunctions(): array
    {
        return [
            new \Twig_Function('get_user', [$this, 'loadUser']),
        ];
    }

    /**
     * Load a user using available user provider.
     *
     * @param  string $username The username to load.
     * @return ?UserInterface
     */
    public function loadUser(string $username): ?UserInterface
    {
        if (!isset($this->cache[$username])) {
            $this->cache[$username] = $this->user_provider->loadUserByUsername($username);
        }
        return $this->cache[$username] ?: null;
    }
}
