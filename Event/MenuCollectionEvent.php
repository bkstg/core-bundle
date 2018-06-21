<?php

namespace Bkstg\CoreBundle\Event;

use Knp\Menu\ItemInterface;
use Symfony\Component\EventDispatcher\Event;

abstract class MenuCollectionEvent extends Event
{
    protected $menu;

    /**
     * Create a new menu collection event.
     *
     * @param ItemInterface $menu The menu to collect.
     */
    public function __construct(ItemInterface $menu)
    {
        $this->menu = $menu;
    }

    /**
     * Get the menu item.
     *
     * @return ItemInterface
     */
    public function getMenu(): ItemInterface
    {
        return $this->menu;
    }
}
