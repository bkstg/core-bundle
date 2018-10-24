<?php

namespace Bkstg\CoreBundle\Tests\Event;

use PHPUnit\Framework\TestCase;
use Bkstg\CoreBundle\Event\EntityPublishedEvent;

class EntityPublishedEventTest extends TestCase
{
    /**
     * Tests the entity published event.
     *
     * @return void
     */
    public function testEvent()
    {
        $object = new \stdClass();
        $event = new EntityPublishedEvent($object);
        $this->assertSame($event->getObject(), $object);
    }
}
