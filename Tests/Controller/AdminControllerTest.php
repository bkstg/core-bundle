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
            $this->templating,
            $this->session,
            $this->form,
            $this->em,
            $this->translator,
            $this->url_generator
        );
    }

    /**
     * Test the redirect action.
     *
     * @return void
     */
    public function testRedirectAction()
    {
        $this->url_generator
            ->expects($this->once())
            ->method('generate')
            ->with('bkstg_admin_dashboard')
            ->willReturn('/test/route');
        $this->assertInstanceOf(RedirectResponse::class, $this->controller->redirectAction());
    }

    /**
     * Test the dashboard action.
     *
     * @return void
     */
    public function testDashboardAction()
    {
        $this->templating
            ->expects($this->once())
            ->method('render')
            ->with('@BkstgCore/Admin/dashboard.html.twig')
            ->willReturn('<html></html>');
        $this->assertInstanceOf(Response::class, $this->controller->dashboardAction());
    }
}
