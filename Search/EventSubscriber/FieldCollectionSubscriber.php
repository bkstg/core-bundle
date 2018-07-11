<?php

namespace Bkstg\CoreBundle\Search\EventSubscriber;

use Bkstg\SearchBundle\Event\FieldCollectionEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class FieldCollectionSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            FieldCollectionEvent::NAME => [
                ['addProductionFields', 0],
            ]
        ];
    }

    public function addProductionFields(FieldCollectionEvent $event)
    {
        $event->addFields([
            'name',
            'description',
            'author',
            'image.name',
        ]);
    }
}
