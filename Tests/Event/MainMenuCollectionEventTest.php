<?php

namespace Bkstg\CoreBundle\Tests\Event;

use Knp\Menu\ItemInterface;
use Symfony\Component\EventDispatcher\Event;
use Bkstg\CoreBundle\Event\MainMenuCollectionEvent;

class MainMenuCollectionEventTest extends MenuCollectionEventTest
{
    /**
     * {@inheritdoc}
     *
     * @param  ItemInterface $item The item to pass.
     *
     * @return Event               The event.
     */
    public function createEvent(ItemInterface $item): Event
    {
        return new MainMenuCollectionEvent($item);
    }
}
