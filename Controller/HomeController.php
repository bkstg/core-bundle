<?php

namespace Bkstg\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\Response;

class HomeController extends Controller
{
    public function homeAction()
    {
        return new Response($this->templating->render('@BkstgCore/Home/home.html.twig'));
    }
}
