<?php

namespace Bkstg\CoreBundle\Tests\Controller;

use PHPUnit\Framework\TestCase;
use Twig\Environment;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Doctrine\ORM\EntityManagerInterface;

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
        $this->templating = $this->createMock(Environment::class);
        $this->session = $this->createMock(SessionInterface::class);
        $this->form = $this->createMock(FormFactoryInterface::class);
        $this->em = $this->createMock(EntityManagerInterface::class);
        $this->translator = $this->createMock(TranslatorInterface::class);
        $this->url_generator = $this->createMock(UrlGeneratorInterface::class);
    }
}
