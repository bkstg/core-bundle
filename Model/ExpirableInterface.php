<?php

declare(strict_types=1);

/*
 * This file is part of the BkstgCoreBundle package.
 * (c) Luke Bainbridge <http://www.lukebainbridge.ca/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Bkstg\CoreBundle\Model;

interface ExpirableInterface
{
    /**
     * Get the datetime this entity will expire.
     *
     * @return \DateTimeInterface
     */
    public function getExpiry(): ?\DateTimeInterface;

    /**
     * Return whether or not the entity is expired.
     *
     * @return bool
     */
    public function isExpired(): bool;
}
