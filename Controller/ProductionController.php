<?php

namespace Bkstg\CoreBundle\Controller;

use Bkstg\CoreBundle\Entity\Production;
use Bkstg\CoreBundle\Form\ProductionSettingsType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
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
        return new RedirectResponse($this->url_generator->generate(
            'bkstg_production_overview',
            ['production_slug' => $production_slug]
        ));
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

    /**
     * Provide a generic settings form for admins.
     *
     * @param  string                        $production_slug The production slug.
     * @param  Request                       $request         The incoming request.
     * @param  AuthorizationCheckerInterface $auth            The authorization checker service.
     * @throws NotFoundHttpException                          When the production is not found.
     * @throws AccessDeniedException                          When the user doesn't have access to the production.
     * @return Response                                       The rendered response.
     */
    public function settingsAction(
        string $production_slug,
        Request $request,
        AuthorizationCheckerInterface $auth
    ): Response {
        // Lookup the production by slug.
        if (null === $production = $this->em->getRepository(Production::class)->findOneBy(['slug' => $production_slug])) {
            throw new NotFoundHttpException();
        }

        // Must be an admin to edit settings.
        if (!$auth->isGranted('GROUP_ROLE_ADMIN', $production)) {
            throw new AccessDeniedException();
        }

        // Create and handle the form.
        $form = $this->form->create(ProductionSettingsType::class, $production);
        $form->handleRequest($request);

        // If form is submitted and valid.
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($production);
            $this->em->flush();

            // Set success message and redirect.
            $this->session->getFlashBag()->add(
                'success',
                $this->translator->trans('production.edited', [
                    '%production%' => $production->getName(),
                ], BkstgCoreBundle::TRANSLATION_DOMAIN)
            );

            return new RedirectResponse($this->url_generator->generate('bkstg_production_settings', [
                'production_slug' => $production->getSlug(),
            ]));
        }

        // Render the form.
        return new Response($this->templating->render('@BkstgCore/Production/settings.html.twig', [
            'form' => $form->createView(),
            'production' => $production,
        ]));
    }
}
