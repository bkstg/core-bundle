<?php

namespace Bkstg\CoreBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Bkstg\CoreBundle\Entity\User;
use Bkstg\CoreBundle\Form\UserType;
use Bkstg\CoreBundle\Form\ProfileType;

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

    public function addAction(Request $request)
    {
        // get entity manager
        $em = $this->em;

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

    public function editAction($user, Request $request)
    {
        // get entity manager
        $em = $this->em;

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
