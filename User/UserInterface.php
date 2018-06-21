<?php

namespace Bkstg\CoreBundle\User;

use Bkstg\MediaBundle\Entity\Media;
use MidnightLuke\GroupSecurityBundle\Model\GroupMemberInterface;
use PhpUnitsOfMeasure\PhysicalQuantity\Length;
use PhpUnitsOfMeasure\PhysicalQuantity\Mass;
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
