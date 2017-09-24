<?php

namespace Bkstg\CoreBundle\Controller;

use Bkstg\CoreBundle\Entity\Production;
use Bkstg\CoreBundle\Entity\ProductionMembership;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ProductionMembershipController extends Controller
{
    public function indexAction($production_slug, Request $request, AuthorizationChecker $auth)
    {
        // Lookup the production by production_slug.
        $production_repo = $this->em->getRepository(Production::class);
        if (null === $production = $production_repo->findOneBy(['slug' => $production_slug])) {
            throw new NotFoundHttpException();
        }

        // Check permissions for this action.
        if (!$auth->isGranted('GROUP_ROLE_USER', $production)) {
            throw new AccessDeniedException();
        }

        // Get memberships for this production.
        $membership_repo = $this->em->getRepository(ProductionMembership::class);
        $memberships = $membership_repo->findBy(['group' => $production]);

        // Return the membership index.
        return new Response($this->templating->render(
            '@BkstgCore/ProductionMembership/index.html.twig',
            [
                'production' => $production,
                'memberships' => $memberships,
            ]
        ));
    }
}
