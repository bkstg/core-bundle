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
use Bkstg\CoreBundle\Event\MenuCollectionEvent;
use Bkstg\CoreBundle\Event\UserMenuCollectionEvent;
use Knp\Menu\FactoryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserMenuSubscriber implements EventSubscriberInterface
{
    private $factory;

    /**
     * Create a new user menu subscriber.
     *
     * @param FactoryInterface $factory The menu factory service.
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
           UserMenuCollectionEvent::NAME => [
               ['addLogoutItem', -50],
           ],
        ];
    }

    /**
     * Create the logout menu item.
     *
     * @param MenuCollectionEvent $event The menu collection event.
     *
     * @return void
     */
    public function addLogoutItem(MenuCollectionEvent $event): void
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
