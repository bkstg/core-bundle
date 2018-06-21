<?php

namespace Bkstg\CoreBundle\Controller;

use Bkstg\CoreBundle\Entity\Production;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ProductionController extends Controller
{
    /**
     * Read a production, simply redirects to the overview.
     *
     * @param  string                        $production_slug The production slug.
     * @param  AuthorizationCheckerInterface $auth            The authorization checker service.
     * @throws NotFoundHttpException                          When the production is not found.
     * @throws AccessDeniedException                          When the user doesn't have access to the production.
     * @return Response                                       The redirect response.
     */
    public function readAction(
        string $production_slug,
        AuthorizationCheckerInterface $auth
    ): RedirectResponse {
        // Lookup the production by production_slug.
        $production_repo = $this->em->getRepository(Production::class);
        if (null === $production = $production_repo->findOneBy(['slug' => $production_slug])) {
            throw new NotFoundHttpException();
        }

        // Check permissions for this action.
        if (!$auth->isGranted('GROUP_ROLE_USER', $production)) {
            throw new AccessDeniedException();
        }

        // Redirect to the production overview.
        return new RedirectResponse($this->url_generator->generate('bkstg_production_overview', ['production_slug' => $production_slug]));
    }

    /**
     * Shows the production image and description, with block for timeline.
     *
     * @param  string                        $production_slug The production slug.
     * @param  AuthorizationCheckerInterface $auth            The authorization checker service.
     * @throws NotFoundHttpException                          When the production is not found.
     * @throws AccessDeniedException                          When the user doesn't have access to the production.
     * @return Response                                       The rendered response.
     */
    public function overviewAction(
        string $production_slug,
        AuthorizationCheckerInterface $auth
    ): Response {
        // Lookup the production by production_slug.
        $production_repo = $this->em->getRepository(Production::class);
        if (null === $production = $production_repo->findOneBy(['slug' => $production_slug])) {
            throw new NotFoundHttpException();
        }

        // Check permissions for this action.
        if (!$auth->isGranted('GROUP_ROLE_USER', $production)) {
            throw new AccessDeniedException();
        }

        // Return response.
        return new Response($this->templating->render('@BkstgCore/Production/overview.html.twig', [
            'production' => $production,
        ]));
    }
}
