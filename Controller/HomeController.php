<?php

namespace Bkstg\CoreBundle\Controller;

use Bkstg\CoreBundle\Entity\Production;
use Bkstg\FOSUserBundle\Entity\ProductionMembership;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class HomeController extends Controller
{
    /**
     * Shows a list of productions the current user has access to.
     *
     * @param  TokenStorageInterface $token_storage The security token storage service.
     * @return Response                             The rendered response.
     */
    public function homeAction(TokenStorageInterface $token_storage): Response
    {
        // Get the user and membership repo.
        $user = $token_storage->getToken()->getUser();
        $membership_repo = $this->em->getRepository(ProductionMembership::class);

        // Get the active memberships for this user.
        $memberships = $membership_repo->findActiveMemberships($user);

        // Render the response.
        return new Response($this->templating->render('@BkstgCore/Home/home.html.twig', [
            'memberships' => $memberships,
        ]));
    }
}
