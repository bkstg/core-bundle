<?php

namespace Bkstg\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends Controller
{
    /**
     * Redirects from the main admin path to the dashboard.
     *
     * @return Response The redirect response.
     */
    public function redirectAction(): Response
    {
        return new RedirectResponse($this->url_generator->generate('bkstg_admin_dashboard'));
    }

    /**
     * Shows the admin dashboard, which has event blocks for other bundles.
     *
     * @return Response The rendered response.
     */
    public function dashboardAction(): Response
    {
        return new Response($this->templating->render(
            '@BkstgCore/Admin/dashboard.html.twig'
        ));
    }
}
