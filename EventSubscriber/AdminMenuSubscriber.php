<?php

namespace Bkstg\CoreBundle\EventSubscriber;

use Bkstg\CoreBundle\Entity\ProductionMembership;
use Bkstg\CoreBundle\Event\AdminMenuCollectionEvent;
use Bkstg\CoreBundle\Event\MenuCollectionEvent;
use Bkstg\CoreBundle\Menu\Item\IconMenuItem;
use Knp\Menu\FactoryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class AdminMenuSubscriber implements EventSubscriberInterface
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
           AdminMenuCollectionEvent::NAME => array(
               array('addDashboardMenuItem', 15),
           )
        );
    }

    public function addDashboardMenuItem(MenuCollectionEvent $event)
    {
        $menu = $event->getMenu();

        // Create overview menu item.
        $dashboard = $this->factory->createItem('Dashboard', [
            'uri' => $this->url_generator->generate('bkstg_admin_dashboard'),
            'extras' => ['icon' => 'dashboard'],
        ]);
        $menu->addChild($dashboard);
    }
}
