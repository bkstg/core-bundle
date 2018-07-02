<?php

declare(strict_types=1);

/*
 * This file is part of the BkstgCoreBundle package.
 * (c) Luke Bainbridge <http://www.lukebainbridge.ca/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Bkstg\CoreBundle\EventListener;

use Bkstg\CoreBundle\Event\EntityPublishedEvent;
use Bkstg\CoreBundle\Model\PublishableInterface;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class PublishableListener
{
    private $event_dispatcher;

    /**
     * Create a new publishable listener.
     *
     * @param EventDispatcherInterface $event_dispatcher The event dispatcher.
     */
    public function __construct(EventDispatcherInterface $event_dispatcher)
    {
        $this->event_dispatcher = $event_dispatcher;
    }

    /**
     * Act before persisting an object.
     *
     * @param LifecycleEventArgs $args The event arguments.
     */
    public function prePersist(LifecycleEventArgs $args): void
    {
        // Get the object and make sure it is what we need.
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

    /**
     * Act before updating an object.
     *
     * @param LifecycleEventArgs $args The event arguments.
     */
    public function preUpdate(LifecycleEventArgs $args): void
    {
        // Get the object and make sure it is what we need.
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
