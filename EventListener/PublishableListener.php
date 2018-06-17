<?php

namespace Bkstg\CoreBundle\EventListener;

use Bkstg\CoreBundle\Event\EntityPublishedEvent;
use Bkstg\CoreBundle\Model\PublishableInterface;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class PublishableListener
{
    private $event_dispatcher;

    public function __construct(EventDispatcherInterface $event_dispatcher)
    {
        $this->event_dispatcher = $event_dispatcher;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $object = $args->getObject();

        if (!$object instanceof PublishableInterface) {
            return;
        }

        // Publish active objects immediately.
        if ($object->isActive()) {
            $object->setPublished(true);
            $event = new EntityPublishedEvent($object);
            $this->event_dispatcher->dispatch(EntityPublishedEvent::NAME, $event);
        } else {
            $object->setPublished(false);
        }
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $object = $args->getObject();

        if (!$object instanceof PublishableInterface) {
            return;
        }

        // Active unpublished objects should be published.
        if ($object->isActive() && !$object->isPublished()) {
            $object->setPublished(true);
            $event = new EntityPublishedEvent($object);
            $this->event_dispatcher->dispatch(EntityPublishedEvent::NAME, $event);
        }
    }
}
