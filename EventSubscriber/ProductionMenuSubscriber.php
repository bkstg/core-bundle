<?php

namespace Bkstg\CoreBundle\EventSubscriber;

use Bkstg\CoreBundle\BkstgCoreBundle;
use Bkstg\CoreBundle\Event\ProductionMenuCollectionEvent;
use Knp\Menu\FactoryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class ProductionMenuSubscriber implements EventSubscriberInterface
{
    private $factory;
    private $auth;

    /**
     * Create a new production menu subscriber.
     *
     * @param FactoryInterface              $factory The menu factory service.
     * @param AuthorizationCheckerInterface $auth    The authorization checker service.
     */
    public function __construct(
        FactoryInterface $factory,
        AuthorizationCheckerInterface $auth
    ) {
        $this->factory = $factory;
        $this->auth = $auth;
    }

    /**
     * Return the subscribed events.
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
           ProductionMenuCollectionEvent::NAME => [
               ['addOverviewItem', 15],
               ['addSettingsItem', -15],
           ]
        ];
    }

    /**
     * Add the overview menu item.
     *
     * @param ProductionMenuCollectionEvent $event The menu collection event.
     * @return void
     */
    public function addOverviewItem(ProductionMenuCollectionEvent $event): void
    {
        $menu = $event->getMenu();
        $group = $event->getGroup();

        // Create overview menu item.
        $overview = $this->factory->createItem('menu_item.overview', [
            'route' => 'bkstg_production_overview',
            'routeParameters' => ['production_slug' => $group->getSlug()],
            'extras' => [
                'icon' => 'dashboard',
                'translation_domain' => BkstgCoreBundle::TRANSLATION_DOMAIN,
            ],
        ]);
        $menu->addChild($overview);
    }

    /**
     * Add the settings menu item.
     *
     * @param ProductionMenuCollectionEvent $event The menu collection event.
     * @return void
     */
    public function addSettingsItem(ProductionMenuCollectionEvent $event): void
    {
        $menu = $event->getMenu();
        $group = $event->getGroup();

        // Should only be available to admins.
        if (!$this->auth->isGranted('GROUP_ROLE_ADMIN', $group)) {
            return;
        }

        // Create settings menu items.
        $settings = $this->factory->createItem('menu_item.settings', [
            'route' => 'bkstg_production_settings',
            'routeParameters' => ['production_slug' => $group->getSlug()],
            'extras' => [
                'icon' => 'wrench',
                'translation_domain' => BkstgCoreBundle::TRANSLATION_DOMAIN,
            ],
        ]);
        $menu->addChild($settings);

        $general = $this->factory->createItem('menu_item.general', [
            'route' => 'bkstg_production_settings',
            'routeParameters' => ['production_slug' => $group->getSlug()],
            'extras' => [
                'translation_domain' => BkstgCoreBundle::TRANSLATION_DOMAIN,
            ],
        ]);
        $settings->addChild($general);
    }
}
