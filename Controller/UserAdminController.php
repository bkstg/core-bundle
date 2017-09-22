<?php

namespace Bkstg\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class UserAdminController extends Controller
{
    public function listAction()
    {
        // Can show either open or closed.
        if ($request->query->has('status')
            && $request->query->get('status') == 'blocked') {
            $productions = $this->em->getRepository(User::class)->findAllBlocked();
        } else {
            $productions = $this->em->getRepository(User::class)->findAllActive();
        }

        // Render the list of productions.
        return new Response($this->templating->render('@BkstgCore/Production/index.html.twig', [
            'productions' => $productions,
        ]));

        return new Response($this->templating->render('@BkstgCore/Admin/home.html.twig'));
    }
}
