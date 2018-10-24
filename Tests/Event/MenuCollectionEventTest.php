<?php

declare(strict_types=1);

/*
 * This file is part of the BkstgCoreBundle package.
 * (c) Luke Bainbridge <http://www.lukebainbridge.ca/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Bkstg\CoreBundle\Tests\Event;

use Knp\Menu\ItemInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\Event;

abstract class MenuCollectionEventTest extends TestCase
{
    /**
     * Test the event.
     *
     * @return void
     */
    public function testEvent(): void
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
