<?php

namespace Bkstg\CoreBundle\Menu;

use Bkstg\CoreBundle\Context\ProductionContextProviderInterface;
use Bkstg\CoreBundle\Event\AdminMenuCollectionEvent;
use Bkstg\CoreBundle\Event\MainMenuCollectionEvent;
use Bkstg\CoreBundle\Event\ProductionMenuCollectionEvent;
use Knp\Menu\FactoryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class MenuBuilder
{
    private $factory;
    private $dispatcher;
    private $group_context;

    /**
     * @param FactoryInterface $factory
     *
     * Add any other dependency you need
     */
    public function __construct(
        FactoryInterface $factory,
        EventDispatcherInterface $dispatcher,
        ProductionContextProviderInterface $group_context
    ) {
        $this->factory = $factory;
        $this->dispatcher = $dispatcher;
        $this->group_context = $group_context;
    }

    public function createAdminMenu(array $options)
    {
        $menu = $this->factory->createItem('root');

        $event = new AdminMenuCollectionEvent($menu);
        $this->dispatcher->dispatch(AdminMenuCollectionEvent::NAME, $event);

        return $menu;
    }

    public function createMainMenu(array $options)
    {
        $menu = $this->factory->createItem('root');

        $event = new MainMenuCollectionEvent($menu);
        $this->dispatcher->dispatch(MainMenuCollectionEvent::NAME, $event);

        return $menu;
    }

    public function createProductionMenu(array $options)
    {
        $menu = $this->factory->createItem('root');

        // No group context means return empty menu.
        if (null === $group = $this->group_context->getContext()) {
            return $menu;
        }

        $menu->setLabel($group->getName());

        // Dispatch event to populate the menu.
        $event = new ProductionMenuCollectionEvent($menu, $group);
        $this->dispatcher->dispatch(ProductionMenuCollectionEvent::NAME, $event);
        return $menu;
    }
}
