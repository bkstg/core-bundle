<?php

declare(strict_types=1);

/*
 * This file is part of the BkstgCoreBundle package.
 * (c) Luke Bainbridge <http://www.lukebainbridge.ca/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Bkstg\CoreBundle\Controller;

use Bkstg\CoreBundle\BkstgCoreBundle;
use Bkstg\CoreBundle\Entity\Production;
use Bkstg\CoreBundle\Form\ProductionType;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ProductionAdminController extends Controller
{
    /**
     * Shows a list of productions.
     *
     * @param Request            $request   The incoming request.
     * @param PaginatorInterface $paginator The paginator service.
     *
     * @return Response The rendered response.
     */
    public function indexAction(
        Request $request,
        PaginatorInterface $paginator
    ): Response {
        // Get the production repo.
        $repo = $this->em->getRepository(Production::class);
        $query = $repo->findAllOpenQuery();

        // Paginate and render the list of productions.
        $productions = $paginator->paginate($query, $request->query->getInt('page', 1));

        return new Response($this->templating->render('@BkstgCore/ProductionAdmin/index.html.twig', [
            'productions' => $productions,
        ]));
    }

    /**
     * Shows a list of archived productions.
     *
     * @param Request            $request   The incoming request.
     * @param PaginatorInterface $paginator The paginator service.
     *
     * @return Response The rendered response.
     */
    public function archiveAction(
        Request $request,
        PaginatorInterface $paginator
    ): Response {
        // Get the production repo.
        $repo = $this->em->getRepository(Production::class);
        $query = $repo->findAllClosedQuery();

        // Paginate and render the list of productions.
        $productions = $paginator->paginate($query, $request->query->getInt('page', 1));

        return new Response($this->templating->render('@BkstgCore/ProductionAdmin/archive.html.twig', [
            'productions' => $productions,
        ]));
    }

    /**
     * Create a new production.
     *
     * @param Request               $request The incoming request.
     * @param TokenStorageInterface $token   The token storage service.
     *
     * @return Response The rendered response.
     */
    public function createAction(
        Request $request,
        TokenStorageInterface $token
    ): Response {
        // Get the current user.
        $user = $token->getToken()->getUser();

        // Create a new production.
        $production = new Production();
        $production->setAuthor($user->getUsername());
        $production->setActive(true);

        // Create and handle the form.
        $form = $this->form->create(ProductionType::class, $production);
        $form->handleRequest($request);

        // Form is submitted and valid.
        if ($form->isSubmitted() && $form->isValid()) {
            // Cascade active property to image.
            if (null !== $image = $production->getImage()) {
                $image->setActive($production->isActive());
            }

            // Cascade active property to banner.
            if (null !== $banner = $production->getBanner()) {
                $banner->setActive($production->isActive());
            }

            // Persist the production
            $this->em->persist($production);
            $this->em->flush();

            // Set success message and redirect.
            $this->session->getFlashBag()->add(
                'success',
                $this->translator->trans('production.created', [
                    '%production%' => $production->getName(),
                ], BkstgCoreBundle::TRANSLATION_DOMAIN)
            );

            return new RedirectResponse($this->url_generator->generate('bkstg_production_admin_index'));
        }

        // Render the form.
        return new Response($this->templating->render('@BkstgCore/ProductionAdmin/create.html.twig', [
            'form' => $form->createView(),
        ]));
    }

    /**
     * Update an existing production.
     *
     * @param int     $id      The id of the production.
     * @param Request $request The incoming request.
     *
     * @throws NotFoundHttpException When the production is not found.
     *
     * @return Repsonse The rendered response.
     */
    public function updateAction(
        int $id,
        Request $request
    ): Response {
        // Lookup the production by id.
        $production_repo = $this->em->getRepository(Production::class);
        if (null === $production = $production_repo->findOneBy(['id' => $id])) {
            throw new NotFoundHttpException();
        }

        // Create and handle the form.
        $form = $this->form->create(ProductionType::class, $production);
        $form->handleRequest($request);

        // Form is submitted and valid.
        if ($form->isSubmitted() && $form->isValid()) {
            // Cascade active property to image.
            if (null !== $image = $production->getImage()) {
                $image->setActive($production->isActive());
            }

            // Cascade active property to banner.
            if (null !== $banner = $production->getBanner()) {
                $banner->setActive($production->isActive());
            }

            // Persist the production
            $this->em->persist($production);
            $this->em->flush();

            // Set success message and redirect.
            $this->session->getFlashBag()->add(
                'success',
                $this->translator->trans('production.edited', [
                    '%production%' => $production->getName(),
                ], BkstgCoreBundle::TRANSLATION_DOMAIN)
            );

            return new RedirectResponse($this->url_generator->generate('bkstg_production_admin_index'));
        }

        // Render the form.
        return new Response($this->templating->render('@BkstgCore/ProductionAdmin/update.html.twig', [
            'production' => $production,
            'form' => $form->createView(),
        ]));
    }

    /**
     * Delete a production from the system.
     *
     * @param int     $id      The id for the production.
     * @param Request $request The incoming Request.
     *
     * @throws NotFoundHttpException When the production is not found.
     *
     * @return Response The incoming response.
     */
    public function deleteAction(
        int $id,
        Request $request
    ): Response {
        // Lookup the production by id.
        $production_repo = $this->em->getRepository(Production::class);
        if (null === $production = $production_repo->findOneBy(['id' => $id])) {
            throw new NotFoundHttpException();
        }

        // Create an empty form to submit.
        $form = $this->form->createBuilder()->getForm();
        $form->handleRequest($request);

        // Delete the production.
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->remove($production);
            $this->em->flush();

            // Set success message and redirect.
            $this->session->getFlashBag()->add(
                'success',
                $this->translator->trans('production.deleted', [
                    '%production%' => $production->getName(),
                ], BkstgCoreBundle::TRANSLATION_DOMAIN)
            );

            return new RedirectResponse($this->url_generator->generate('bkstg_production_admin_index'));
        }

        // Render the form.
        return new Response($this->templating->render('@BkstgCore/ProductionAdmin/delete.html.twig', [
            'production' => $production,
            'form' => $form->createView(),
        ]));
    }
}
