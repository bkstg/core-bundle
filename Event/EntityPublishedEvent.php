<?php

declare(strict_types=1);

/*
 * This file is part of the BkstgCoreBundle package.
 * (c) Luke Bainbridge <http://www.lukebainbridge.ca/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Bkstg\CoreBundle\Event;

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
