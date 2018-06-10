<?php

namespace Bkstg\CoreBundle\Controller;

use Bkstg\CoreBundle\Entity\Production;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class HomeController extends Controller
{
    public function homeAction(TokenStorageInterface $token_storage)
    {
        $user = $token_storage->getToken()->getUser();
        $production_repo = $this->em->getRepository(Production::class);
        $productions = $production_repo->findAll();
        return new Response($this->templating->render('@BkstgCore/Home/home.html.twig', [
            'productions' => $productions,
        ]));
    }
}
