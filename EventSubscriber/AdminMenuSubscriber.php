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
               array('addProductionMenuItem', -15),
               array('addMonitorMenuItem', -15),
           ),
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

    public function addProductionMenuItem(MenuCollectionEvent $event)
    {
        $menu = $event->getMenu();

        // Create productions menu item.
        $productions = $this->factory->createItem('Productions', [
            'uri' => $this->url_generator->generate('bkstg_production_admin_list'),
            'extras' => ['icon' => 'list'],
        ]);
        $menu->addChild($productions);
    }

    public function addMonitorMenuItem(MenuCollectionEvent $event)
    {
        $menu = $event->getMenu();

        // Create users menu item.
        $health = $this->factory->createItem('System Health', [
            'uri' => $this->url_generator->generate('liip_monitor_health_interface'),
            'extras' => ['icon' => 'medkit'],
        ]);
        $menu->addChild($health);
    }
}
