<?php

namespace Bkstg\CoreBundle\Tests\Controller;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Bkstg\CoreBundle\Controller\AdminController;

class AdminControllerTest extends ControllerTest
{
    /**
     * Set up this test.
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->controller = new AdminController(
            $this->templating->reveal(),
            $this->session->reveal(),
            $this->form->reveal(),
            $this->em->reveal(),
            $this->translator->reveal(),
            $this->url_generator->reveal()
        );
    }

    /**
     * Test the redirect action.
     *
     * @return void
     */
    public function testRedirectAction()
    {
        // Add behaviour for url generator.
        $this->url_generator->generate('bkstg_admin_dashboard')->willReturn('/test/route');

        // Assertions for the response.
        $response = $this->controller->redirectAction();
        $this->assertInstanceOf(RedirectResponse::class, $this->controller->redirectAction());
        $this->assertEquals('/test/route', $response->getTargetUrl());
    }

    /**
     * Test the dashboard action.
     *
     * @return void
     */
    public function testDashboardAction()
    {
        // Add behaviour for templating.
        $this->templating->render('@BkstgCore/Admin/dashboard.html.twig')->willReturn('<html></html>');

        // Assertions for the response.
        $response = $this->controller->dashboardAction();
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals('<html></html>', $response->getContent());
    }
}