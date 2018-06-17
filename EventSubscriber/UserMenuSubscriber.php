<?php

namespace Bkstg\CoreBundle\EventSubscriber;

use Bkstg\CoreBundle\BkstgCoreBundle;
use Bkstg\CoreBundle\Entity\Production;
use Bkstg\CoreBundle\Event\UserMenuCollectionEvent;
use Bkstg\CoreBundle\Event\MenuCollectionEvent;
use Knp\Menu\FactoryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserMenuSubscriber implements EventSubscriberInterface
{
    private $factory;

    public function __construct(FactoryInterface $factory) {
        $this->factory = $factory;
    }

    public static function getSubscribedEvents()
    {
        // return the subscribed events, their methods and priorities
        return [
           UserMenuCollectionEvent::NAME => [
               ['addLogoutItem', -50],
           ],
        ];
    }

    public function addLogoutItem(MenuCollectionEvent $event)
    {
        $menu = $event->getMenu();

        $separator = $this->factory->createItem('separator', [
            'extras' => [
                'separator' => true,
                'translation_domain' => false,
            ],
        ]);
        $menu->addChild($separator);
        // Create logout menu item.
        $logout = $this->factory->createItem('menu_item.logout', [
            'route' => 'fos_user_security_logout',
            'extras' => [
                'icon' => 'sign-out',
                'translation_domain' => BkstgCoreBundle::TRANSLATION_DOMAIN,
            ],
        ]);
        $menu->addChild($logout);
    }
}
