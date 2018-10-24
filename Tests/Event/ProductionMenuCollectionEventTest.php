<?php

declare(strict_types=1);

/*
 * This file is part of the BkstgCoreBundle package.
 * (c) Luke Bainbridge <http://www.lukebainbridge.ca/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Bkstg\CoreBundle\Tests\Event;

use Bkstg\CoreBundle\Event\ProductionMenuCollectionEvent;
use Knp\Menu\ItemInterface;
use MidnightLuke\GroupSecurityBundle\Model\GroupInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\Event;

class ProductionMenuCollectionEventTest extends TestCase
{
    /**
     * Test the event.
     *
     * @return void
     */
    public function testEvent(): void
    {
        $group = $this->prophesize(GroupInterface::class);
        $item = $this->prophesize(ItemInterface::class);
        $event = new ProductionMenuCollectionEvent($item->reveal(), $group->reveal());
        $this->assertInstanceOf(ItemInterface::class, $event->getMenu());
        $this->assertInstanceOf(GroupInterface::class, $event->getGroup());
    }
}
