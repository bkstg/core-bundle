<?php

namespace Bkstg\CoreBundle\EventSubscriber;

use Bkstg\CoreBundle\Entity\Production;
use Bkstg\CoreBundle\Event\MainMenuCollectionEvent;
use Bkstg\CoreBundle\Event\MenuCollectionEvent;
use Bkstg\CoreBundle\Menu\Item\IconMenuItem;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Menu\FactoryInterface;
use MidnightLuke\GroupSecurityBundle\Model\GroupMemberInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class MainMenuSubscriber implements EventSubscriberInterface
{

    private $factory;
    private $url_generator;
    private $em;
    private $token_storage;
    private $auth;

    public function __construct(
        FactoryInterface $factory,
        UrlGeneratorInterface $url_generator,
        EntityManagerInterface $em,
        TokenStorageInterface $token_storage,
        AuthorizationCheckerInterface $auth
    ) {
        $this->factory = $factory;
        $this->url_generator = $url_generator;
        $this->em = $em;
        $this->token_storage = $token_storage;
        $this->auth = $auth;
    }

    public static function getSubscribedEvents()
    {
        // return the subscribed events, their methods and priorities
        return [
           MainMenuCollectionEvent::NAME => [
               ['addHomeMenuItem', 50],
               ['addProductionMenuItems', 25],
               ['addAdminMenuItem', -25],
               ['addLogoutMenuItem', -50],
           ],
        ];
    }

    public function addHomeMenuItem(MenuCollectionEvent $event)
    {
    }

    public function addLogoutMenuItem(MenuCollectionEvent $event)
    {
        $menu = $event->getMenu();

        $logout = $this->factory->createItem('Logout', [
            'uri' => $this->url_generator->generate('fos_user_security_logout'),
            'extras' => ['icon' => 'sign-out'],
        ]);
        $menu->addChild($logout);
    }

    public function addAdminMenuItem(MenuCollectionEvent $event)
    {
        if (!$this->auth->isGranted('ROLE_ADMIN')) {
            return;
        }

        $menu = $event->getMenu();

        // Create overview menu item.
        $admin = $this->factory->createItem('Admin', [
            'uri' => $this->url_generator->generate('bkstg_admin_redirect'),
            'extras' => ['icon' => 'wrench'],
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
        $production_item = $this->factory->createItem('Productions');
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
                    'uri' => $this->url_generator->generate(
                        'bkstg_production_show',
                        ['production_slug' => $membership->getGroup()->getSlug()]
                    ),
                    'extras' => ['translation_domain' => false],
                ]);
            }
        }

        // Add all children (in alpha order) to production item.
        usort($items, function ($a, $b) {
            return strcasecmp($a->getLabel(), $b->getLabel());
        });
        $production_item->setChildren($items);

        // Add production admin.
        if ($this->auth->isGranted('ROLE_ADMIN')) {
            $production_item->addChild($this->factory->createItem('<admin-separator>', [
                'extras' => ['translation_domain' => false, 'separator' => true],
            ]));
            $production_item->addChild($this->factory->createItem('All productions', [
                'uri' => $this->url_generator->generate('bkstg_production_admin_list'),
                'extras' => ['icon' => 'list'],
            ]));
        }

        // Add the production menu item to the main menu.
        $menu = $event->getMenu();
        $menu->addChild($production_item);
    }
}
