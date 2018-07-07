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
     * If an object is active set published value.
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
        } else {
            $object->setPublished(false);
        }
    }

    /**
     * If an object is added as published fire published event.
     *
     * @param LifecycleEventArgs $args The lifecycle arguments.
     */
    public function postPersist(LifecycleEventArgs $args): void
    {
        // Get the object and make sure it is what we need.
        $object = $args->getObject();
        if (!$object instanceof PublishableInterface) {
            return;
        }

        // Execute published event after the object is persisted.
        if ($object->isPublished()) {
            $event = new EntityPublishedEvent($object);
            $this->event_dispatcher->dispatch(EntityPublishedEvent::NAME, $event);
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
        }
    }

    /**
     * If an object is updated and published fire published event.
     *
     * @param LifecycleEventArgs $args The lifecycle arguments.
     */
    public function postUpdate(LifecycleEventArgs $args): void
    {
        // Get the object and make sure it is what we need.
        $object = $args->getObject();
        if (!$object instanceof PublishableInterface) {
            return;
        }

        // Get the changeset from the unit of work.
        $uow = $args->getObjectManager()->getUnitOfWork();
        $changeset = $uow->getEntityChangeset($object);

        // If the published state changed fire the published event.
        if (isset($changeset['published'])
            && false === $changeset['published'][0]
            && true === $changeset['published'][1]) {
            $event = new EntityPublishedEvent($object);
            $this->event_dispatcher->dispatch(EntityPublishedEvent::NAME, $event);
        }
    }
}
