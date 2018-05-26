<?php

namespace Bkstg\CoreBundle\EventSubscriber;

use Bkstg\CoreBundle\BkstgCoreBundle;
use Bkstg\CoreBundle\Entity\ProductionMembership;
use Bkstg\CoreBundle\Event\AdminMenuCollectionEvent;
use Bkstg\CoreBundle\Event\MenuCollectionEvent;
use Bkstg\CoreBundle\Menu\Item\IconMenuItem;
use Knp\Menu\FactoryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Translation\TranslatorInterface;

class AdminMenuSubscriber implements EventSubscriberInterface
{

    private $factory;
    private $url_generator;
    private $translator;

    public function __construct(
        FactoryInterface $factory,
        UrlGeneratorInterface $url_generator,
        TranslatorInterface $translator
    ) {
        $this->factory = $factory;
        $this->url_generator = $url_generator;
        $this->translator = $translator;
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
        $dashboard = $this->factory->createItem('dashboard', [
            'label' => $this->translator->trans('menu_item.dashboard', [], BkstgCoreBundle::TRANSLATION_DOMAIN),
            'uri' => $this->url_generator->generate('bkstg_admin_dashboard'),
            'extras' => ['icon' => 'dashboard'],
        ]);
        $menu->addChild($dashboard);
    }

    public function addProductionMenuItem(MenuCollectionEvent $event)
    {
        $menu = $event->getMenu();

        // Create productions menu item.
        $productions = $this->factory->createItem('productions', [
            'label' => $this->translator->trans('menu_item.productions', [], BkstgCoreBundle::TRANSLATION_DOMAIN),
            'uri' => $this->url_generator->generate('bkstg_production_admin_list'),
            'extras' => ['icon' => 'list'],
        ]);
        $menu->addChild($productions);
    }
}
