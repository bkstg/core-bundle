<?php

namespace Bkstg\CoreBundle\Controller;

use Bkstg\CoreBundle\Form\Type\ProductionType;
use Bkstg\CoreBundle\Model\Group\GroupMembershipInterface;
use Bkstg\CoreBundle\Model\Production;
use Bkstg\CoreBundle\Model\ProductionMembership;
use Bkstg\CoreBundle\Model\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Class for handling production CRUD operations.
 */
class ProductionController extends Controller
{
    /**
     * Shows a list of all productions in the system.
     *
     * @param Request $request
     * @param AuthorizationChecker $auth
     * @return Response The response to the browser.
     */
    public function indexAction(Request $request, AuthorizationChecker $auth)
    {
        // Must have the global admin role to access.
        if (!$auth->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }

        $productions = $this->em->getRepository(Production::class)->findAll();
        return new Response($this->templating->render('@BkstgCoreBundle/Production/index.html.twig', [
            'productions' => $productions,
        ]));
    }

    /**
     * Create a new production on the site.
     *
     * Adds a new membership for the user that creates the production as an
     * admin of the new production.
     *
     * @param Request $request
     * @param AuthorizationChecker $auth
     * @param TokenStorageInterface $token
     * @return Response
     */
    public function createAction(
        Request $request,
        AuthorizationChecker $auth,
        TokenStorageInterface $token
    ) {
        // Must have the global admin role to access.
        if (!$auth->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }

        // Get some basic information about the user.
        $user = $token->getToken()->getUser();

        // Create a new production.
        $production = new Production();
        $production->setAuthor($user);

        // Create and handle the form.
        $form = $this->form->create(ProductionType::class, $production);
        $form->handleRequest($request);

        // Form is submitted and valid.
        if ($form->isSubmitted() && $form->isValid()) {
            // Persist the production
            $this->em->persist($production);

            // Create a new membership for this user as an admin.
            $membership = new ProductionMembership();
            $membership->setGroup($production);
            $membership->setMember($user);
            $membership->addRole('GROUP_ROLE_ADMIN');
            $membership->setStatus(GroupMembershipInterface::STATUS_ACTIVE);

            // Persist the membership and flush.
            $this->em->persist($membership);
            $this->em->flush();

            // Set success message and redirect.
            $this->session->getFlashBag()->add(
                'success',
                $this->translator->trans('Production "%production%" created.', [
                    '%production%' => $production->getName(),
                ])
            );
            $this->session->getFlashBag()->add(
                'success',
                $this->translator->trans('You have been made a member of "%production%".', [
                    '%production%' => $production->getName(),
                ])
            );
            return new RedirectResponse($this->url_generator->generate('bkstg_production_list'));
        }

        // Render the form.
        return new Response($this->templating->render('@BkstgCoreBundle/Production/create.html.twig', [
            'form' => $form->createView(),
        ]));
    }
}