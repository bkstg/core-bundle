<?php

namespace Bkstg\CoreBundle\Controller;

use Bkstg\CoreBundle\Entity\User;
use Bkstg\CoreBundle\Form\ProfileType;
use Bkstg\CoreBundle\Form\Type\UserType;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Form\Type\ProfileFormType;
use FOS\UserBundle\Mailer\MailerInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use FOS\UserBundle\Util\TokenGeneratorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class UserController extends Controller
{
    public function indexAction(Request $request)
    {
        // Can show either enabled or blocked.
        if ($request->query->has('status')
            && $request->query->get('status') == 'blocked') {
            $users = $this->em->getRepository(User::class)->findBy(['enabled' => false]);
        } else {
            $users = $this->em->getRepository(User::class)->findBy(['enabled' => true]);
        }

        // Render the list of users.
        return new Response($this->templating->render('@BkstgCore/User/index.html.twig', [
            'users' => $users,
        ]));
    }

    public function createAction(
        Request $request,
        UserManagerInterface $user_manager,
        TokenGeneratorInterface $token_generator,
        MailerInterface $mailer
    ) {
        // Create a new user.
        $user = $user_manager->createUser();

        // Create and handle the form.
        $form = $this->form->create(UserType::class, $user);
        $form->handleRequest($request);

        // Form is submitted and valid.
        if ($form->isSubmitted() && $form->isValid()) {
            // Set a random password.
            $user->setPlainPassword(md5(uniqid($user->getUsername(), true)));
            $user->setConfirmationToken($token_generator->generateToken());

            // Persist the user
            $user_manager->updateUser($user);
            $mailer->sendConfirmationEmailMessage($user);

            // Set success message and redirect.
            $this->session->getFlashBag()->add(
                'success',
                $this->translator->trans('User "%user%" created.', [
                    '%user%' => $user->getUsername(),
                ])
            );
            return new RedirectResponse($this->url_generator->generate('bkstg_user_list'));
        }

        // Render the form.
        return new Response($this->templating->render('@BkstgCore/User/create.html.twig', [
            'form' => $form->createView(),
        ]));
    }

    public function updateAction($id, Request $request)
    {
        if (null === $user = $this->em->getRepository(User::class)->findOneBy(['id' => $id])) {
            throw new NotFoundHttpException();
        }

        // Create and handle the form.
        $form = $this->form->create(UserType::class, $user);
        $form->handleRequest($request);

        // Form is submitted and valid.
        if ($form->isSubmitted() && $form->isValid()) {
            // Persist the user
            $this->em->persist($user);
            $this->em->flush();

            // Set success message and redirect.
            $this->session->getFlashBag()->add(
                'success',
                $this->translator->trans('User "%user%" created.', [
                    '%user%' => $user->getName(),
                ])
            );
            return new RedirectResponse($this->url_generator->generate('bkstg_user_list'));
        }

        // Render the form.
        return new Response($this->templating->render('@BkstgCore/User/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]));
    }

    public function deleteAction($user, Request $request)
    {
        // get entity manager
        $em = $this->em;

        // disable and persist the user
        $user->setEnabled(false);
        $em->persist($user);
        $em->flush();

        // add success message and redirect
        $this->addFlash('warning', 'User deleted!');
        return $this->redirectToRoute('bkstg_user_home');
    }

    public function viewAction($user, Request $request)
    {
        return $this->render('BkstgCoreBundle:User:user.html.twig', array('user' => $user));
    }

    public function editProfileAction($user, Request $request)
    {
        // get entity manager
        $em = $this->em;
        $app_user = $this->get('security.token_storage')->getToken()->getUser();

        if ($app_user->getId() !== $user->getId()) {
            throw new AccessDeniedException;
        }

        // create the form for this
        $form = $this->createForm(new ProfileType('Bkstg\CoreBundle\Entity\User'), $user);

        // handle form request
        $form->handleRequest($request);
        if ($form->isValid()) {
            // set user defaults
            $user->setUsername($user->getEmail());

            // persist the user
            $em->persist($user);
            $em->flush();

            // add success message and redirect
            $this->addFlash('success', 'User "' . $user . '" edited successfully!');
            return $this->redirectToRoute('bkstg_user_home');
        }

        // get message manager
        $message_manager = $this->get('message.manager');

        return $this->render('BkstgCoreBundle:User:profile_form.html.twig', array(
            'form' => $form->createView(),
            'message_manager' => $message_manager,
        ));
    }

    public function redirectProfile() {
        $app_user = $this->get('security.token_storage')->getToken()->getUser();
        return $this->redirectToRoute('bkstg_user_view_user', array('user' => $app_user->getId()));
    }
}
