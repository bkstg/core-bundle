<?php

declare(strict_types=1);

/*
 * This file is part of the BkstgCoreBundle package.
 * (c) Luke Bainbridge <http://www.lukebainbridge.ca/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Bkstg\CoreBundle\Context;

use Bkstg\CoreBundle\Entity\Production;

interface ProductionContextProviderInterface
{
    /**
     * The purpose of this function is to return a specific production.
     *
     * @return ?Production
     */
    public function getContext(): ?Production;
}
