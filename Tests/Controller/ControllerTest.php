<?php

namespace Bkstg\CoreBundle\Tests\Controller;

use PHPUnit\Framework\TestCase;
use Twig\Environment;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Prophecy\Argument;
use Prophecy\Doubler\DoubleInterface;
use Bkstg\CoreBundle\User\UserInterface;

abstract class ControllerTest extends TestCase
{
    protected $templating;
    protected $session;
    protected $form;
    protected $em;
    protected $translator;
    protected $url_generator;

    /**
     * Set up this test.
     *
     * @return void
     */
    public function setUp()
    {
        $this->templating = $this->prophesize(Environment::class);
        $this->session = $this->prophesize(SessionInterface::class);
        $this->form = $this->prophesize(FormFactoryInterface::class);
        $this->em = $this->prophesize(EntityManagerInterface::class);
        $this->translator = $this->prophesize(TranslatorInterface::class);
        $this->url_generator = $this->prophesize(UrlGeneratorInterface::class);

        // Create stubs for render function.
        $this->templating
            ->render(Argument::type('string'), Argument::type('array'))
            ->will(function ($args) {
                return '<html></html>';
            });
        $this->templating
            ->render(Argument::type('string'))
            ->will(function ($args) {
                return '<html></html>';
            });

        // Create stubs for the url generator functions.
        $this->url_generator
            ->generate(Argument::type('string'), Argument::type('array'))
            ->will(function ($args) {
                return '/test/route';
            });
        $this->url_generator
            ->generate(Argument::type('string'))
            ->will(function ($args) {
                return '/test/route';
            });

        // Create stub repo and for getting repo.
        $this->em
            ->getRepository(Argument::type('string'))
            ->will(function ($args) {
                $repo = $this->mock(EntityRepository::class);
                $repo->findOneBy(Argument::type('array'))->willReturn(new $args[0]);
                $repo->findBy(Argument::type('array'))->willReturn([new $arg[0], new $arg[0]]);
                return $repo->reveal();
            });
    }

    /**
     * Create a mock token storage service.
     *
     * @param UserInterface $user The user that will be returned.
     *
     * @return DoubleInterface
     */
    public function getMockTokenStorage(UserInterface $user = null)
    {
        // Create a user if not provided.
        if ($user === null) {
            $user = $this->prophesize(UserInterface::class)->reveal();
        }

        // Create the token, as this is likely what will be needed.
        $token = $this->prophesize(TokenInterface::class);
        $token->getUser()->willReturn($user);
        $token_storage = $this->prophesize(TokenStorageInterface::class);
        $token_storage->getToken()->willReturn($token->reveal());
        return $token_storage;
    }

    /**
     * Create a mock paginator with no results.
     *
     * @return DoubleInterface
     */
    public function getMockPaginator()
    {
        $paginator = $this->prophesize(PaginatorInterface::class);
        $paginator
            ->paginate(Argument::type(AbstractQuery::class), Argument::type('int'))
            ->will(function ($args) {
                return [];
            });

        return $paginator;
    }
}
