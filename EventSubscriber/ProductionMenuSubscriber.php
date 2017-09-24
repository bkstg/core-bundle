<?php

namespace Bkstg\CoreBundle\EventSubscriber;

use Bkstg\CoreBundle\Event\ProductionMenuCollectionEvent;
use Bkstg\CoreBundle\Menu\Item\IconMenuItem;
use Knp\Menu\FactoryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ProductionMenuSubscriber implements EventSubscriberInterface
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
           ProductionMenuCollectionEvent::NAME => array(
               array('addMenuItem', -15),
           )
        );
    }

    public function addMenuItem(ProductionMenuCollectionEvent $event)
    {
        $menu = $event->getMenu();
        $group = $event->getGroup();

        // Create overview menu item.
        $overview = $this->factory->createItem('Overview', [
            'uri' => $this->url_generator->generate(
                'bkstg_production_overview',
                ['production_slug' => $group->getSlug()]
            ),
            'extras' => ['icon' => 'dashboard'],
        ]);
        $members = $this->factory->createItem('Members', [
            'uri' => $this->url_generator->generate(
                'bkstg_membership_list',
                ['production_slug' => $group->getSlug()]
            ),
            'extras' => ['icon' => 'users'],
        ]);
        $menu->addChild($overview);
        $menu->addChild($members);
    }
}
