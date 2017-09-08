<?php

namespace Bkstg\CoreBundle\Controller;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\Translation\TranslatorInterface;

abstract class Controller
{
    private $templating;
    private $session;
    private $em;
    private $translator;
    private $url_generator;

    public function __construct(
        EngineInterface $templating,
        SessionInterface $session,
        EntityManagerInterface $em,
        TranslatorInterface $translator,
        UrlGeneratorInterface $url_generator
    ) {
        $this->templating = $templating;
        $this->session = $session;
        $this->em = $em;
        $this->translator = $translator;
        $this->url_generator = $url_generator;
    }
}
