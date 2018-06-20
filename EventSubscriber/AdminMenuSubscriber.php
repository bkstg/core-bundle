<?php

namespace Bkstg\CoreBundle\EventSubscriber;

use Bkstg\CoreBundle\BkstgCoreBundle;
use Bkstg\CoreBundle\Event\AdminMenuCollectionEvent;
use Bkstg\CoreBundle\Event\MenuCollectionEvent;
use Knp\Menu\FactoryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AdminMenuSubscriber implements EventSubscriberInterface
{
    private $factory;

    public function __construct(FactoryInterface $factory) {
        $this->factory = $factory;
    }

    public static function getSubscribedEvents()
    {
        // return the subscribed events, their methods and priorities
        return array(
           AdminMenuCollectionEvent::NAME => array(
               array('addDashboardMenuItem', 15),
               array('addProductionMenuItem', -15),
           ),
        );
    }

    public function addDashboardMenuItem(MenuCollectionEvent $event)
    {
        $menu = $event->getMenu();

        // Create overview menu item.
        $dashboard = $this->factory->createItem('menu_item.dashboard', [
            'route' => 'bkstg_admin_dashboard',
            'extras' => [
                'icon' => 'dashboard',
                'translation_domain' => BkstgCoreBundle::TRANSLATION_DOMAIN,
            ],
        ]);
        $menu->addChild($dashboard);
    }

    public function addProductionMenuItem(MenuCollectionEvent $event)
    {
        $menu = $event->getMenu();

        // Create productions menu item.
        $productions = $this->factory->createItem('menu_item.productions', [
            'route' => 'bkstg_production_admin_index',
            'extras' => [
                'icon' => 'list',
                'translation_domain' => BkstgCoreBundle::TRANSLATION_DOMAIN,
            ],
        ]);
        $menu->addChild($productions);

        // Create productions menu item.
        $list = $this->factory->createItem('menu_item.productions', [
            'route' => 'bkstg_production_admin_index',
            'extras' => [
                'translation_domain' => BkstgCoreBundle::TRANSLATION_DOMAIN,
            ],
        ]);
        $productions->addChild($list);

        // Create productions menu item.
        $archive = $this->factory->createItem('menu_item.archive', [
            'route' => 'bkstg_production_admin_archive',
            'extras' => [
                'translation_domain' => BkstgCoreBundle::TRANSLATION_DOMAIN,
            ],
        ]);
        $productions->addChild($archive);
    }
}
