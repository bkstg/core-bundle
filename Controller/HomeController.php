<?php

namespace Bkstg\CoreBundle\Controller;

use Bkstg\CoreBundle\Entity\Production;
use Bkstg\FOSUserBundle\Entity\ProductionMembership;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class HomeController extends Controller
{
    public function homeAction(TokenStorageInterface $token_storage)
    {
        $user = $token_storage->getToken()->getUser();
        $membership_repo = $this->em->getRepository(ProductionMembership::class);

        // Get the active memberships for this user.
        $memberships = $membership_repo->findActiveMemberships($user);

        return new Response($this->templating->render('@BkstgCore/Home/home.html.twig', [
            'memberships' => $memberships,
        ]));
    }
}
