<?php

namespace Bkstg\CoreBundle\Menu\Builder;

class ProductionMenuBuilder
{
    private $factory;
    private $dispatcher;

    /**
     * @param FactoryInterface $factory
     *
     * Add any other dependency you need
     */
    public function __construct(
        FactoryInterface $factory,
        EventDispatcherInterface $dispatcher
    ) {
        $this->factory = $factory;
        $this->dispatcher = $dispatcher;
    }

    public function createMenu(array $options)
    {
        $menu = $this->factory->createItem('root');

        $event = new ProductionMenuCollectionEvent($menu);
        $this->dispatcher->dispatch(ProductionMenuCollectionEvent::NAME, $event);

        return $menu;
    }
}
