<?php

namespace Bkstg\CoreBundle\Event;

use Knp\Menu\ItemInterface;
use Symfony\Component\EventDispatcher\Event;

class EntityPublishedEvent extends Event
{
    const NAME = 'bkstg.core.entity_published';

    private $object;

    public function __construct($object)
    {
        $this->object = $object;
    }

    public function getObject()
    {
        return $this->object;
    }
}
