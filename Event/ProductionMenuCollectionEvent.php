<?php

namespace Bkstg\CoreBundle\Event;

use MidnightLuke\GroupSecurityBundle\Model\GroupInterface;
use Knp\Menu\ItemInterface;

class ProductionMenuCollectionEvent extends MenuCollectionEvent
{
    const NAME = 'bkstg.core.menu.production_collection';

    private $group;

    /**
     * Create a new production menu collection event.
     *
     * @param ItemInterface  $item  The menu item being collected for.
     * @param GroupInterface $group The group being collected for.
     */
    public function __construct(ItemInterface $item, GroupInterface $group)
    {
        $this->group = $group;
        parent::__construct($item);
    }

    /**
     * Get the group.
     *
     * @return GroupInterface
     */
    public function getGroup(): GroupInterface
    {
        return $this->group;
    }
}
