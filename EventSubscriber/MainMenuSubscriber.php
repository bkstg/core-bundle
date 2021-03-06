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
use Bkstg\CoreBundle\Entity\Production;
use Bkstg\CoreBundle\Event\MainMenuCollectionEvent;
use Bkstg\CoreBundle\Event\MenuCollectionEvent;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Menu\FactoryInterface;
use MidnightLuke\GroupSecurityBundle\Model\GroupMemberInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class MainMenuSubscriber implements EventSubscriberInterface
{
    private $factory;
    private $em;
    private $token_storage;
    private $auth;

    /**
     * Create a new menu subscriber.
     *
     * @param FactoryInterface              $factory       The menu item factory.
     * @param EntityManagerInterface        $em            The entity manager service.
     * @param TokenStorageInterface         $token_storage The token storage service.
     * @param AuthorizationCheckerInterface $auth          The authorization checker service.
     */
    public function __construct(
        FactoryInterface $factory,
        EntityManagerInterface $em,
        TokenStorageInterface $token_storage,
        AuthorizationCheckerInterface $auth
    ) {
        $this->factory = $factory;
        $this->em = $em;
        $this->token_storage = $token_storage;
        $this->auth = $auth;
    }

    /**
     * Return the subscribed events.
     *
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
           MainMenuCollectionEvent::NAME => [
               ['addProductionMenuItems', 25],
               ['addAdminMenuItem', -25],
           ],
        ];
    }

    /**
     * Add the admin menu item.
     *
     * @param MenuCollectionEvent $event The menu collection event.
     *
     * @return void
     */
    public function addAdminMenuItem(MenuCollectionEvent $event): void
    {
        if (!$this->auth->isGranted('ROLE_ADMIN')) {
            return;
        }

        $menu = $event->getMenu();

        // Create admin menu item.
        $admin = $this->factory->createItem('menu_item.admin', [
            'route' => 'bkstg_admin_redirect',
            'extras' => [
                'icon' => 'wrench',
                'translation_domain' => BkstgCoreBundle::TRANSLATION_DOMAIN,
            ],
        ]);
        $menu->addChild($admin);
    }

    /**
     * Add the production menu items.
     *
     * @param MenuCollectionEvent $event The menu collection event.
     *
     * @return void
     */
    public function addProductionMenuItems(MenuCollectionEvent $event): void
    {
        // We only operate on group member users.
        $user = $this->token_storage->getToken()->getUser();
        if (!$user instanceof GroupMemberInterface) {
            return;
        }

        // Create productions menu dropdown item.
        $productions = $this->factory->createItem('menu_item.productions', [
            'extras' => ['translation_domain' => BkstgCoreBundle::TRANSLATION_DOMAIN],
        ]);

        $items = [];
        foreach ($user->getMemberships() as $membership) {
            // Get the group for this membership, if not a production skip out.
            $group = $membership->getGroup();

            // If the membership and production are active and not expired.
            if ($membership->isActive()
                && !$membership->isExpired()
                && $group->isActive()
                && !$group->isExpired()) {
                // The membership is good, add a menu item.
                $items[] = $this->factory->createItem($membership->getGroup()->getName(), [
                    'route' => 'bkstg_production_read',
                    'routeParameters' => ['production_slug' => $membership->getGroup()->getSlug()],
                    'extras' => ['translation_domain' => false],
                ]);
            }
        }

        if (0 === count($user->getMemberships())) {
            $productions->addChild($this->factory->createItem('menu_item.no_productions', [
                'uri' => '#',
                'extras' => [
                    'translation_domain' => BkstgCoreBundle::TRANSLATION_DOMAIN,
                ],
            ]));
        } else {
            // Add all children (in alpha order) to production item.
            usort($items, function ($a, $b) {
                return strcasecmp($a->getLabel(), $b->getLabel());
            });
            $productions->setChildren($items);
        }

        // Add the production menu item to the main menu.
        $menu = $event->getMenu();
        $menu->addChild($productions);
    }
}
