<?php

declare(strict_types=1);

/*
 * This file is part of the BkstgCoreBundle package.
 * (c) Luke Bainbridge <http://www.lukebainbridge.ca/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
