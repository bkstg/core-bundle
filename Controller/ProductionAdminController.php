<?php

namespace Bkstg\CoreBundle\Controller;

use Bkstg\CoreBundle\Entity\Production;
use Bkstg\CoreBundle\Form\Type\ProductionType;
use Bkstg\FOSUserBundle\Entity\ProductionMembership;
use MidnightLuke\GroupSecurityBundle\Model\GroupMembershipInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ProductionAdminController extends Controller
{
    public function indexAction(Request $request)
    {
        // Can show either open or closed.
        if ($request->query->has('status')
            && $request->query->get('status') == 'closed') {
            $productions = $this->em->getRepository(Production::class)->findAllClosed();
        } else {
            $productions = $this->em->getRepository(Production::class)->findAllOpen();
        }

        // Render the list of productions.
        return new Response($this->templating->render('@BkstgCore/Production/index.html.twig', [
            'productions' => $productions,
        ]));
    }

    public function createAction(
        Request $request,
        TokenStorageInterface $token
    ) {
        // Get some basic information about the user.
        $user = $token->getToken()->getUser();

        // Create a new production.
        $production = new Production();
        $production->setAuthor($user->getUsername());

        // Create and handle the form.
        $form = $this->form->create(ProductionType::class, $production);
        $form->handleRequest($request);

        // Form is submitted and valid.
        if ($form->isSubmitted() && $form->isValid()) {
            // Persist the production
            $this->em->persist($production);
            $this->em->flush();

            // Set success message and redirect.
            $this->session->getFlashBag()->add(
                'success',
                $this->translator->trans('Production "%production%" created.', [
                    '%production%' => $production->getName(),
                ])
            );
            return new RedirectResponse($this->url_generator->generate('bkstg_production_admin_list'));
        }

        // Render the form.
        return new Response($this->templating->render('@BkstgCore/Production/create.html.twig', [
            'form' => $form->createView(),
        ]));
    }

    public function updateAction(
        $id,
        Request $request,
        TokenStorageInterface $token
    ) {
        // Lookup the production by production_slug.
        $production_repo = $this->em->getRepository(Production::class);
        if (null === $production = $production_repo->findOneBy(['id' => $id])) {
            throw new NotFoundHttpException();
        }

        // Create and handle the form.
        $form = $this->form->create(ProductionType::class, $production);
        $form->handleRequest($request);

        // Form is submitted and valid.
        if ($form->isSubmitted() && $form->isValid()) {
            // Persist the production
            $this->em->persist($production);
            $this->em->flush();

            // Set success message and redirect.
            $this->session->getFlashBag()->add(
                'success',
                $this->translator->trans('Production "%production%" edited.', [
                    '%production%' => $production->getName(),
                ])
            );
            return new RedirectResponse($this->url_generator->generate('bkstg_production_admin_list'));
        }

        // Render the form.
        return new Response($this->templating->render('@BkstgCore/Production/edit.html.twig', [
            'production' => $production,
            'form' => $form->createView(),
        ]));
    }
}