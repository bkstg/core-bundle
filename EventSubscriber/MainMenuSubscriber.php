<?php

namespace Bkstg\CoreBundle\EventSubscriber;

use Bkstg\CoreBundle\Entity\ProductionMembership;
use Bkstg\CoreBundle\Event\MainMenuCollectionEvent;
use Bkstg\CoreBundle\Event\MenuCollectionEvent;
use Bkstg\CoreBundle\Menu\Item\IconMenuItem;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Menu\FactoryInterface;
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
        return array(
           MainMenuCollectionEvent::NAME => array(
               array('addHomeMenuItem', 15),
               array('addAdminMenuItem', 10),
               array('addProductionMenuItems', -15),
           )
        );
    }

    public function addHomeMenuItem(MenuCollectionEvent $event)
    {
        $menu = $event->getMenu();

        // Create overview menu item.
        $home = $this->factory->createItem('Home', [
            'uri' => $this->url_generator->generate('bkstg_production_list'),
            'extras' => ['icon' => 'home'],
        ]);
        $menu->addChild($home);
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
        $menu = $event->getMenu();

        // Create productions menu dropdown.
        $productions = $this->factory->createItem('Productions');
        $membership_repo = $this->em->getRepository(ProductionMembership::class);
        $memberships = $membership_repo->findActiveMemberships($this->token_storage->getToken()->getUser());

        foreach ($memberships as $membership) {
            $membership_item = $this->factory->createItem($membership->getGroup()->getName(), [
                'uri' => $this->url_generator->generate(
                    'bkstg_production_show',
                    ['slug' => $membership->getGroup()->getSlug()]
                ),
            ]);
            $productions->addChild($membership_item);
        }
        $menu->addChild($productions);
    }
}
