<?php

namespace Bkstg\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends Controller
{
    public function redirectAction()
    {
        return new RedirectResponse($this->url_generator->generate('bkstg_admin_dashboard'));
    }

    public function dashboardAction()
    {
        return new Response($this->templating->render(
            '@BkstgCore/Admin/dashboard.html.twig'
        ));
    }
}
