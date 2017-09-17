<?php

namespace Bkstg\CoreBundle\EventSubscriber;

use Bkstg\CoreBundle\Event\MenuCollectionEvent;
use Bkstg\CoreBundle\Event\ProductionMenuCollectionEvent;
use Bkstg\CoreBundle\Menu\Item\MenuItem;
use Knp\Menu\FactoryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ProductionMenuSubscriber implements EventSubscriberInterface
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
           ProductionMenuCollectionEvent::NAME => array(
               array('addMenuItem', -15),
           )
        );
    }

    public function addMenuItem(MenuCollectionEvent $event)
    {
        $menu = $event->getMenu();
        $group = $event->getGroup();

        // Create home menu item.
        $home = new MenuItem('Overview', 'page', $this->factory);
        $home->setUri($this->url_generator->generate('bkstg_production_show', ['slug' => $group->getSlug()]));
        $menu->addChild($home);
    }
}
