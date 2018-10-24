<?php

namespace Bkstg\CoreBundle\Tests\Event;

use PHPUnit\Framework\TestCase;
use Knp\Menu\ItemInterface;
use Symfony\Component\EventDispatcher\Event;

abstract class MenuCollectionEventTest extends TestCase
{
    /**
     * Test the event.
     *
     * @return void
     */
    public function testEvent()
    {
        $item = $this->prophesize(ItemInterface::class);
        $event = $this->createEvent($item->reveal());
        $this->assertInstanceOf(ItemInterface::class, $event->getMenu());
    }

    /**
     * Create an instance of the event.
     *
     * @param ItemInterface $item The item to pass to the event.
     *
     * @return Event
     */
    abstract public function createEvent(ItemInterface $item): Event;
}
