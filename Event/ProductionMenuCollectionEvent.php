<?php

namespace Bkstg\CoreBundle\Event;

use MidnightLuke\GroupSecurityBundle\Model\GroupInterface;
use Knp\Menu\ItemInterface;

class ProductionMenuCollectionEvent extends MenuCollectionEvent
{

    const NAME = 'bkstg.core.menu.production_collection';

    private $group;

    public function __construct(ItemInterface $item, GroupInterface $group)
    {
        $this->group = $group;
        parent::__construct($item);
    }

    public function getGroup()
    {
        return $this->group;
    }
}
