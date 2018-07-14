<?php

declare(strict_types=1);

/*
 * This file is part of the BkstgCoreBundle package.
 * (c) Luke Bainbridge <http://www.lukebainbridge.ca/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Bkstg\CoreBundle\EventSubscriber;

use Bkstg\CoreBundle\BkstgCoreBundle;
use Bkstg\CoreBundle\Event\AdminMenuCollectionEvent;
use Bkstg\CoreBundle\Event\MenuCollectionEvent;
use Knp\Menu\FactoryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AdminMenuSubscriber implements EventSubscriberInterface
{
    private $factory;

    /**
     * Create a new admin menu subscriber.
     *
     * @param FactoryInterface $factory The menu item factory.
     */
    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    /**
     * Return the subscribed events.
     *
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
           AdminMenuCollectionEvent::NAME => [
               ['addDashboardMenuItem', 15],
               ['addProductionMenuItem', -15],
           ],
        ];
    }

    /**
     * Add the dashboard menu item.
     *
     * @param MenuCollectionEvent $event The menu collection event.
     *
     * @return void
     */
    public function addDashboardMenuItem(MenuCollectionEvent $event): void
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

    /**
     * Add the production menu items.
     *
     * @param MenuCollectionEvent $event The menu collection event.
     *
     * @return void
     */
    public function addProductionMenuItem(MenuCollectionEvent $event): void
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
