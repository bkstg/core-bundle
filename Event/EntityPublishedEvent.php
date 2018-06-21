<?php

namespace Bkstg\CoreBundle\Event;

use Knp\Menu\ItemInterface;
use Symfony\Component\EventDispatcher\Event;

class EntityPublishedEvent extends Event
{
    const NAME = 'bkstg.core.entity_published';

    private $object;

    /**
     * Create a new event.
     *
     * @param mixed $object The object being published.
     */
    public function __construct($object)
    {
        $this->object = $object;
    }

    /**
     * Get the published object.
     *
     * @return mixed
     */
    public function getObject()
    {
        return $this->object;
    }
}
