<?php
 declare(strict_types=1);

/*
 * This file is part of the BkstgCoreBundle package.
 * (c) Luke Bainbridge <http://www.lukebainbridge.ca/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Bkstg\CoreBundle\User;

interface ProductionRoleInterface
{
    /**
     * Get the designation of the role (ie "Cast" or "Crew").
     *
     * @return ?string The role's designation.
     */
    public function getDesignation(): ?string;

    /**
     * Get the name of the role (ie "Romeo").
     *
     * @return ?string The role's name.
     */
    public function getName(): ?string;
}
