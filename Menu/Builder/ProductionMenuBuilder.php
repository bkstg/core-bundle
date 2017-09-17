<?php

namespace Bkstg\CoreBundle\Menu\Builder;

use Bkstg\CoreBundle\Context\GroupContextProvider;
use Bkstg\CoreBundle\Event\ProductionMenuCollectionEvent;
use Knp\Menu\FactoryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ProductionMenuBuilder
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
        GroupContextProvider $group_context
    ) {
        $this->factory = $factory;
        $this->dispatcher = $dispatcher;
        $this->group_context = $group_context;
    }

    public function createMenu(array $options)
    {
        $menu = $this->factory->createItem('root');

        $production = $this->group_context->getContext();
        $event = new ProductionMenuCollectionEvent($menu, $this->group_context->getContext());
        $this->dispatcher->dispatch(ProductionMenuCollectionEvent::NAME, $event);

        return $menu;
    }
}
