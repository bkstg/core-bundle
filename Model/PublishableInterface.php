<?php

declare(strict_types=1);

/*
 * This file is part of the BkstgCoreBundle package.
 * (c) Luke Bainbridge <http://www.lukebainbridge.ca/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Bkstg\CoreBundle\Model;

interface PublishableInterface extends ActiveInterface
{
    /**
     * Should return true if the entity is published.
     *
     * @return bool
     */
    public function isPublished(): bool;

    /**
     * Allows the published flag to be set.
     *
     * @param bool $published The value of published.
     *
     * @return PublishableInterface
     */
    public function setPublished(bool $published);
}
