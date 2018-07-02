<?php

declare(strict_types=1);

/*
 * This file is part of the BkstgCoreBundle package.
 * (c) Luke Bainbridge <http://www.lukebainbridge.ca/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Bkstg\CoreBundle\User;

use MidnightLuke\GroupSecurityBundle\Model\GroupMemberInterface;
use Symfony\Component\Security\Core\User\UserInterface as BaseUserInterface;

interface UserInterface extends BaseUserInterface, GroupMemberInterface
{
    /**
     * Ensure that user has an email address.
     *
     * @return ?string
     */
    public function getEmail(): ?string;
}
