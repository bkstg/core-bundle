<?php

declare(strict_types=1);

/*
 * This file is part of the BkstgCoreBundle package.
 * (c) Luke Bainbridge <http://www.lukebainbridge.ca/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Bkstg\CoreBundle\Event;

use Knp\Menu\ItemInterface;
use MidnightLuke\GroupSecurityBundle\Model\GroupInterface;

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
