<?php

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

    public static function getSubscribedEvents()
    {
        // return the subscribed events, their methods and priorities
        return [
           MainMenuCollectionEvent::NAME => [
               ['addProductionMenuItems', 25],
               ['addAdminMenuItem', -25],
               ['addLogoutMenuItem', -50],
           ],
        ];
    }

    public function addLogoutMenuItem(MenuCollectionEvent $event)
    {
        $menu = $event->getMenu();

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

    public function addAdminMenuItem(MenuCollectionEvent $event)
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

    public function addProductionMenuItems(MenuCollectionEvent $event)
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
            if ($membership->getStatus()
                && !$membership->isExpired()
                && $group->getStatus()
                && !$group->isExpired()) {
                // The membership is good, add a menu item.
                $items[] = $this->factory->createItem($membership->getGroup()->getName(), [
                    'route' => 'bkstg_production_show',
                    'routeParameters' => ['production_slug' => $membership->getGroup()->getSlug()],
                    'extras' => ['translation_domain' => false],
                ]);
            }
        }

        // Add all children (in alpha order) to production item.
        usort($items, function ($a, $b) {
            return strcasecmp($a->getLabel(), $b->getLabel());
        });
        $productions->setChildren($items);

        // Add the production menu item to the main menu.
        $menu = $event->getMenu();
        $menu->addChild($productions);
    }
}
