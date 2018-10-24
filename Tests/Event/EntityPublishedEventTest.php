<?php

declare(strict_types=1);

/*
 * This file is part of the BkstgCoreBundle package.
 * (c) Luke Bainbridge <http://www.lukebainbridge.ca/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Bkstg\CoreBundle\Tests\Event;

use Bkstg\CoreBundle\Event\EntityPublishedEvent;
use PHPUnit\Framework\TestCase;

class EntityPublishedEventTest extends TestCase
{
    /**
     * Tests the entity published event.
     *
     * @return void
     */
    public function testEvent(): void
    {
        $object = new \stdClass();
        $event = new EntityPublishedEvent($object);
        $this->assertSame($event->getObject(), $object);
    }
}
