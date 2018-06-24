<?php

namespace Bkstg\CoreBundle\Twig;

use Bkstg\CoreBundle\Entity\Production;
use Bkstg\CoreBundle\User\MembershipProviderInterface;
use Bkstg\CoreBundle\User\ProductionMembershipInterface;
use Bkstg\CoreBundle\User\UserInterface;

class MembershipExtension extends \Twig_Extension
{
    private $membership_provider;
    private $cache = [];

    /**
     * Build a new membership extension.
     *
     * @param MembershipProviderInterface $membership_provider The membership provider service.
     */
    public function __construct(MembershipProviderInterface $membership_provider)
    {
        $this->membership_provider = $membership_provider;
    }

    /**
     * Return set of twig functions.
     *
     * @return array
     */
    public function getFunctions(): array
    {
        return [
            new \Twig_Function('get_membership', [$this, 'loadMembership']),
        ];
    }

    /**
     * Load a membership using available membership provider.
     *
     * @param  Production    $production The production to load a membership for.
     * @param  UserInterface $user       The user to load a membership for.
     * @return ?ProductionMembershipInterface
     */
    public function loadMembership(Production $production, UserInterface $user): ?ProductionMembershipInterface
    {
        return $this->membership_provider->loadMembership($production, $user);
    }
}
