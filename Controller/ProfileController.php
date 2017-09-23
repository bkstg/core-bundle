<?php

namespace Bkstg\CoreBundle\Controller;

use Bkstg\CoreBundle\Entity\Profile;
use Bkstg\CoreBundle\Form\Type\ProfileType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ProfileController extends Controller
{
    public function redirectAction()
    {
    }

    public function createAction(Request $request, TokenStorageInterface $token_storage)
    {
        $user = $token_storage->getToken()->getUser();
        $profile_repo = $this->em->getRepository(Profile::class);
        if (null !== $profile = $profile_repo->findOneBy(['author' => $user])) {
            throw new AccessDeniedException($this->translator->trans('You can only have one global profile.'));
        }

        $profile = new Profile();
        $profile->setAuthor($user);

        $form = $this->form->create(ProfileType::class, $profile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Persist the profile and flush.
            $this->em->persist($profile);
            $this->em->flush();

            // Set success message and redirect.
            $this->session->getFlashBag()->add(
                'success',
                $this->translator->trans('Your global profile has been created.')
            );

            return new RedirectResponse($this->url_generator->generate(
                'bkstg_profile_show',
                ['id' => $profile->getId()]
            ));
        }

        return new Response($this->templating->render(
            '@BkstgCore/Profile/create.html.twig',
            ['form' => $form->createView()]
        ));
    }

    public function readAction($id)
    {
    }

    public function updateAction($id)
    {
    }

    public function deleteAction($id)
    {
    }

    public function productionRedirectAction($slug)
    {
    }

    public function productionCreateAction($slug)
    {
    }

    public function productionReadAction($slug, $id)
    {
    }

    public function productionUpdateAction($slug, $id)
    {
    }

    public function productionDeleteAction($slug, $id)
    {
    }
}
