<?php

declare(strict_types=1);

/*
 * This file is part of the BkstgCoreBundle package.
 * (c) Luke Bainbridge <http://www.lukebainbridge.ca/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Bkstg\CoreBundle\Search\EventSubscriber;

use Bkstg\SearchBundle\Event\FieldCollectionEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class FieldCollectionSubscriber implements EventSubscriberInterface
{
    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            FieldCollectionEvent::NAME => [
                ['addProductionFields', 0],
            ],
        ];
    }

    /**
     * Set the fields that should be searched.
     *
     * @param FieldCollectionEvent $event The field collection event.
     *
     * @return void
     */
    public function addProductionFields(FieldCollectionEvent $event): void
    {
        $event->addFields([
            'name',
            'description',
            'author',
            'image.name',
        ]);
    }
}
