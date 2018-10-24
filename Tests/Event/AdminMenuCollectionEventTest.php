<?php

declare(strict_types=1);

/*
 * This file is part of the BkstgCoreBundle package.
 * (c) Luke Bainbridge <http://www.lukebainbridge.ca/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Bkstg\CoreBundle\Tests\Event;

use Bkstg\CoreBundle\Event\AdminMenuCollectionEvent;
use Knp\Menu\ItemInterface;
use Symfony\Component\EventDispatcher\Event;

class AdminMenuCollectionEventTest extends MenuCollectionEventTest
{
    /**
     * {@inheritdoc}
     *
     * @param ItemInterface $item The item to pass.
     *
     * @return Event The event.
     */
    public function createEvent(ItemInterface $item): Event
    {
        return new AdminMenuCollectionEvent($item);
    }
}
