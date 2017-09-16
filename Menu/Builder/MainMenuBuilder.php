<?php

namespace Bkstg\CoreBundle\Menu\Builder;

use Bkstg\CoreBundle\Event\MainMenuCollectionEvent;
use Bkstg\CoreBundle\Event\MenuCollectionEvent;
use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class MainMenuBuilder
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

        $event = new MainMenuCollectionEvent($menu);
        $this->dispatcher->dispatch(MainMenuCollectionEvent::NAME, $event);

        return $menu;
    }
}
