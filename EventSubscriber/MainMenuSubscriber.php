<?php

namespace Bkstg\CoreBundle\EventSubscriber;

use Bkstg\CoreBundle\Event\MainMenuCollectionEvent;
use Bkstg\CoreBundle\Event\MenuCollectionEvent;
use Bkstg\CoreBundle\Menu\Item\IconMenuItem;
use Knp\Menu\FactoryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class MainMenuSubscriber implements EventSubscriberInterface
{

    private $factory;
    private $url_generator;

    public function __construct(
        FactoryInterface $factory,
        UrlGeneratorInterface $url_generator
    ) {
        $this->factory = $factory;
        $this->url_generator = $url_generator;
    }

    public static function getSubscribedEvents()
    {
        // return the subscribed events, their methods and priorities
        return array(
           MainMenuCollectionEvent::NAME => array(
               array('addMenuItem', -15),
           )
        );
    }

    public function addMenuItem(MenuCollectionEvent $event)
    {
        $menu = $event->getMenu();

        // Create home menu item.
        $home = new IconMenuItem('Home', 'home', $this->factory);
        $home->setUri($this->url_generator->generate('bkstg_production_list'));
        $menu->addChild($home);
    }
}
