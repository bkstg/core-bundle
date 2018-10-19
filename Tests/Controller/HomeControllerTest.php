<?php

namespace Bkstg\CoreBundle\Tests\Controller;

use Bkstg\CoreBundle\Controller\HomeController;
use Bkstg\CoreBundle\User\UserInterface;
use Bkstg\CoreBundle\User\MembershipProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityRepository;

class HomeControllerTest extends ControllerTest
{
    /**
     * Set up this test.
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->controller = new HomeController(
            $this->templating,
            $this->session,
            $this->form,
            $this->em,
            $this->translator,
            $this->url_generator
        );
    }

    /**
     * Test the home action.
     *
     * @return void
     */
    public function testHomeAction()
    {
        $user = $this->createMock(UserInterface::class);

        // Create a mock membership provider.
        $membership_provider = $this->createMock(MembershipProviderInterface::class);
        $membership_provider
            ->expects($this->once())
            ->method('loadActiveMembershipsByUser')
            ->with($user)
            ->willReturn([]);

        // Create a mock token.
        $token = $this->createMock(TokenInterface::class);
        $token
            ->expects($this->once())
            ->method('getUser')
            ->willReturn($user);
        $token_storage = $this->createMock(TokenStorageInterface::class);
        $token_storage
            ->expects($this->once())
            ->method('getToken')
            ->willReturn($token);

        // Test that rendering happens.
        $this->templating
            ->expects($this->once())
            ->method('render')
            ->with('@BkstgCore/Home/home.html.twig', ['memberships' => []])
            ->willReturn('<html></html>');

        // Assert that a response is returned.
        $this->assertInstanceOf(Response::class, $this->controller->homeAction($token_storage, $membership_provider));
    }
}
