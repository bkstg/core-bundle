<?php

namespace Bkstg\CoreBundle\Event;

use Knp\Menu\ItemInterface;
use Symfony\Component\EventDispatcher\Event;

abstract class MenuCollectionEvent extends Event
{

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
