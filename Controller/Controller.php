<?php

namespace Bkstg\CoreBundle\Controller;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Form\FormFactoryInterface;

abstract class Controller
{
    protected $templating;
    protected $session;
    protected $form;
    protected $em;
    protected $translator;
    protected $url_generator;

    public function __construct(
        EngineInterface $templating,
        SessionInterface $session,
        FormFactoryInterface $form,
        EntityManagerInterface $em,
        TranslatorInterface $translator,
        UrlGeneratorInterface $url_generator
    ) {
        $this->templating = $templating;
        $this->session = $session;
        $this->form = $form;
        $this->em = $em;
        $this->translator = $translator;
        $this->url_generator = $url_generator;
    }
}
