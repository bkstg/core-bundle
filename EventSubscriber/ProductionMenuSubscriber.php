<?php

namespace Bkstg\CoreBundle\EventSubscriber;

use Bkstg\CoreBundle\BkstgCoreBundle;
use Bkstg\CoreBundle\Event\ProductionMenuCollectionEvent;
use Bkstg\CoreBundle\Menu\Item\IconMenuItem;
use Knp\Menu\FactoryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Translation\TranslatorInterface;

class ProductionMenuSubscriber implements EventSubscriberInterface
{

    private $factory;
    private $url_generator;
    private $auth;
    private $translator;

    public function __construct(
        FactoryInterface $factory,
        UrlGeneratorInterface $url_generator,
        AuthorizationCheckerInterface $auth,
        TranslatorInterface $translator
    ) {
        $this->factory = $factory;
        $this->url_generator = $url_generator;
        $this->auth = $auth;
        $this->translator = $translator;
    }

    public static function getSubscribedEvents()
    {
        // return the subscribed events, their methods and priorities
        return array(
           ProductionMenuCollectionEvent::NAME => array(
               array('addOverviewItem', 15),
               array('addSettingsItem', -15),
           )
        );
    }

    public function addOverviewItem(ProductionMenuCollectionEvent $event)
    {
        $menu = $event->getMenu();
        $group = $event->getGroup();

        // Create overview menu item.
        $overview = $this->factory->createItem('overview', [
            'label' => $this->translator->trans('menu_item.overview', [], BkstgCoreBundle::TRANSLATION_DOMAIN),
            'uri' => $this->url_generator->generate(
                'bkstg_production_overview',
                ['production_slug' => $group->getSlug()]
            ),
            'extras' => ['icon' => 'dashboard'],
        ]);
        $menu->addChild($overview);
    }

    public function addSettingsItem(ProductionMenuCollectionEvent $event)
    {
        $menu = $event->getMenu();
        $group = $event->getGroup();

        if (!$this->auth->isGranted('GROUP_ROLE_ADMIN', $group)) {
            return;
        }

        // Create settings menu item.
        $settings = $this->factory->createItem('settings', [
            'label' => $this->translator->trans('menu_item.settings', [], BkstgCoreBundle::TRANSLATION_DOMAIN),
            'uri' => $this->url_generator->generate(
                'bkstg_production_settings_general',
                ['production_slug' => $group->getSlug()]
            ),
            'extras' => ['icon' => 'wrench'],
        ]);
        $general = $this->factory->createItem('general', [
            'label' => $this->translator->trans('menu_item.general', [], BkstgCoreBundle::TRANSLATION_DOMAIN),
            'uri' => $this->url_generator->generate(
                'bkstg_production_settings_general',
                ['production_slug' => $group->getSlug()]
            ),
        ]);
        $settings->addChild($general);
        $menu->addChild($settings);
    }
}
