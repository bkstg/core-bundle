<?php

namespace Bkstg\CoreBundle\Tests\Event;

use Knp\Menu\ItemInterface;
use Symfony\Component\EventDispatcher\Event;
use Bkstg\CoreBundle\Event\ProductionMenuCollectionEvent;
use MidnightLuke\GroupSecurityBundle\Model\GroupInterface;
use PHPUnit\Framework\TestCase;

class ProductionMenuCollectionEventTest extends TestCase
{
    /**
     * Test the event.
     *
     * @return void
     */
    public function testEvent()
    {
        $group = $this->prophesize(GroupInterface::class);
        $item = $this->prophesize(ItemInterface::class);
        $event = new ProductionMenuCollectionEvent($item->reveal(), $group->reveal());
        $this->assertInstanceOf(ItemInterface::class, $event->getMenu());
        $this->assertInstanceOf(GroupInterface::class, $event->getGroup());
    }
}
