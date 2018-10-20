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
            $this->templating->reveal(),
            $this->session->reveal(),
            $this->form->reveal(),
            $this->em->reveal(),
            $this->translator->reveal(),
            $this->url_generator->reveal()
        );
    }

    /**
     * Test the home action.
     *
     * @return void
     */
    public function testHomeAction()
    {
        $user = $this->prophesize(UserInterface::class);

        // Create a mock membership provider.
        $membership_provider = $this->prophesize(MembershipProviderInterface::class);
        $membership_provider->loadActiveMembershipsByUser($user->reveal())->willReturn([]);

        // Create a mock token.
        $token = $this->prophesize(TokenInterface::class);
        $token->getUser()->willReturn($user->reveal());
        $token_storage = $this->prophesize(TokenStorageInterface::class);
        $token_storage->getToken()->willReturn($token->reveal());

        // Test that rendering happens.
        $this->templating->render('@BkstgCore/Home/home.html.twig', ['memberships' => []])->willReturn('<html></html>');

        // Assert that a response is returned.
        $response = $this->controller->homeAction($token_storage->reveal(), $membership_provider->reveal());
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }
}
