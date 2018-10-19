<?php

declare(strict_types=1);

/*
 * This file is part of the BkstgCoreBundle package.
 * (c) Luke Bainbridge <http://www.lukebainbridge.ca/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Bkstg\CoreBundle\Controller;

use Bkstg\FOSUserBundle\Entity\ProductionMembership;
use Bkstg\CoreBundle\User\MembershipProviderInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class HomeController extends Controller
{
    /**
     * Shows a list of productions the current user has access to.
     *
     * @param TokenStorageInterface       $token_storage       The security token storage service.
     * @param MembershipProviderInterface $membership_provider The membership provider service.
     *
     * @return Response The rendered response.
     */
    public function homeAction(
        TokenStorageInterface $token_storage,
        MembershipProviderInterface $membership_provider
    ): Response {
        // Get the user and memberships.
        $user = $token_storage->getToken()->getUser();
        $memberships = $membership_provider->loadActiveMembershipsByUser($user);

        // Render the response.
        return new Response($this->templating->render('@BkstgCore/Home/home.html.twig', [
            'memberships' => $memberships,
        ]));
    }
}
