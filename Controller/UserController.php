<?php

namespace Bkstg\CoreBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation as Http;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Bkstg\CoreBundle\Entity\User;
use Bkstg\CoreBundle\Form\UserType;
use Bkstg\CoreBundle\Form\ProfileType;
use Bkstg\CoreBundle\Manager\MessageManager;

/**
 * @Route\Route("/users")
 */
class UserController extends Controller
{
    /**
     * @Route\Route("/", name="bkstg_user_home")
     */
    public function indexAction(Http\Request $request)
    {
        // get current user and entity manager
        $em = $this->getDoctrine()->getManager();

        // get resources
        $dql = "SELECT u FROM BkstgCoreBundle:User u WHERE u.enabled = 1 ORDER BY u.lastName ASC";
        $query = $em->createQuery($dql);

        $form = $this->createForm(new UserType('Bkstg\CoreBundle\Entity\User'), new User(), array());

        // paginate
        $paginator = $this->get('knp_paginator');
        $users = $paginator->paginate($query, $request->query->getInt('page', 1), 100);

        // get message manager
        $message_manager = $this->get('message.manager');

        return $this->render('BkstgCoreBundle:User:users.html.twig', array(
            'users' => $users,
            'form' => $form->createView(),
            'message_manager' => $message_manager,
        ));
    }

    /**
     * @Route\Route("/add", name="bkstg_user_add_user")
     * @Route\Security("has_role('ROLE_EDITOR')")
     */
    public function addAction(Http\Request $request)
    {
        // get entity manager
        $em = $this->getDoctrine()->getManager();

        // create the form for this
        $user = new User();
        $form = $this->createForm(new UserType('Bkstg\CoreBundle\Entity\User'), $user);

        // handle form request
        $form->handleRequest($request);
        if ($form->isValid()) {

            // set user defaults
            $user->setUsername($user->getEmail());
            $user->setPlainPassword(md5(uniqid($user->getUsername(), true)));
            $user->setUseGravatar(false);

            // generate password reset token
            $tokenGenerator = $this->container->get('fos_user.util.token_generator');
            $user->setConfirmationToken($tokenGenerator->generateToken());
            $user->setPasswordRequestedAt(new \DateTime());

            // set the user for the positions
            foreach ($user->getPositions() as $position) {
                $position->setUser($user);
            }

            // send confirmation email
            $resetUrl = $this->generateUrl('fos_user_resetting_reset', array('token' => $user->getConfirmationToken()));
            $message = \Swift_Message::newInstance()
                ->setSubject('New account created for you on the Backstage!')
                ->setFrom('info@bkstg.net')
                ->setTo($user->getEmail())
                ->setBody($this->renderView(
                    'BkstgCoreBundle:Emails/User:registration.html.twig',
                    array(
                        'user' => $user,
                    )),
                    'text/html'
                );
            $this->get('mailer')->send($message);

            // persist the new user
            $em->persist($user);
            $em->flush();

            // add success message and redirect
            $this->addFlash('success', 'New user "' . $user . '" created!');
            return $this->redirectToRoute('bkstg_user_home');
        }

        // get message manager
        $message_manager = $this->get('message.manager');

        // render the form
        return $this->render('BkstgCoreBundle:User:user_form.html.twig', array(
            'title' => 'Add a User',
            'description' => 'Use this form to add a user to the backstage.',
            'form' => $form->createView(),
            'message_manager' => $message_manager,
        ));
    }

    /**
     * @Route\Route("/edit/{user}", name="bkstg_user_edit_user")
     * @Route\ParamConverter("user", class="BkstgCoreBundle:User")
     * @Route\Security("has_role('ROLE_EDITOR')")
     */
    public function editAction(User $user, Http\Request $request)
    {
        // get entity manager
        $em = $this->getDoctrine()->getManager();

        // create the form for this
        $form = $this->createForm(new UserType('Bkstg\CoreBundle\Entity\User'), $user);

        // original schedule items
        $original_positions = new ArrayCollection();

        // Create an ArrayCollection of the current ScheduleItem objects in the database
        foreach ($user->getPositions() as $position) {
            $original_positions->add($position);
        }

        // handle form request
        $form->handleRequest($request);
        if ($form->isValid()) {
            // remove the relationship between the ScheduleItem and the Schedule
            foreach ($original_positions as $position) {
                if (false === $user->getPositions()->contains($position)) {
                    // delete the schedule item
                    $em->remove($position);
                }
            }

            // set user defaults
            $user->setUsername($user->getEmail());

            // set the user for the positions
            foreach ($user->getPositions() as $position) {
                $position->setUser($user);
            }

            // persist the user
            $em->persist($user);
            $em->flush();

            // add success message and redirect
            $this->addFlash('success', 'User "' . $user . '" edited successfully!');
            return $this->redirectToRoute('bkstg_user_home');
        }

        // get message manager
        $message_manager = $this->get('message.manager');

        // render the form
        return $this->render('BkstgCoreBundle:User:user_form.html.twig', array(
            'title' => 'Edit User',
            'description' => 'Use this form to edit a user on the backstage.',
            'form' => $form->createView(),
            'message_manager' => $message_manager,
        ));
    }

    /**
     * @Route\Route("/delete/{user}", name="bkstg_user_delete_user")
     * @Route\ParamConverter("user", class="BkstgCoreBundle:User")
     * @Route\Security("has_role('ROLE_EDITOR')")
     */
    public function deleteAction(User $user, Http\Request $request)
    {
        // get entity manager
        $em = $this->getDoctrine()->getManager();

        // disable and persist the user
        $user->setEnabled(false);
        $em->persist($user);
        $em->flush();

        // add success message and redirect
        $this->addFlash('warning', 'User deleted!');
        return $this->redirectToRoute('bkstg_user_home');
    }

    /**
     * @Route\Route("/view/{user}", name="bkstg_user_view_user")
     * @Route\ParamConverter("user", class="BkstgCoreBundle:User")
     */
    public function viewAction(User $user, Http\Request $request)
    {
        return $this->render('BkstgCoreBundle:User:user.html.twig', array('user' => $user));
    }

    /**
     * @Route\Route("/edit/{user}/profile", name="bkstg_user_edit_profile_user")
     * @Route\ParamConverter("user", class="BkstgCoreBundle:User")
     */
    public function editProfileAction(User $user, Http\Request $request)
    {
        // get entity manager
        $em = $this->getDoctrine()->getManager();
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

    /**
     * Overriding the FOSUserBundle route
     *
     * @Route\Route("/fos-profile", name="fos_user_profile_show")
     */
    public function redirectProfile() {
        $app_user = $this->get('security.token_storage')->getToken()->getUser();
        return $this->redirectToRoute('bkstg_user_view_user', array('user' => $app_user->getId()));
    }
}
