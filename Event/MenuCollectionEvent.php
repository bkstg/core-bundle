<?php

namespace Bkstg\CoreBundle\Event;

use Knp\Menu\ItemInterface;
use Symfony\Component\EventDispatcher\Event;

class MenuCollectionEvent extends Event {
    const NAME = 'bkstg.menu_collection';

    protected $menu;

    public function __construct(ItemInterface $menu)
    {
        $this->menu = $menu;
    }

    public function getMenu()
    {
        return $this->menu;
    }
}
