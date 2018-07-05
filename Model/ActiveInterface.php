<?php

declare(strict_types=1);

/*
 * This file is part of the BkstgCoreBundle package.
 * (c) Luke Bainbridge <http://www.lukebainbridge.ca/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Bkstg\CoreBundle\Model;

interface ActiveInterface
{
    /**
     * Should return true if the entity is active.
     *
     * @return bool
     */
    public function isActive(): bool;
}
