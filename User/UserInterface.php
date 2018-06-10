<?php

namespace Bkstg\CoreBundle\User;

use Bkstg\MediaBundle\Entity\Media;
use PhpUnitsOfMeasure\PhysicalQuantity\Length;
use PhpUnitsOfMeasure\PhysicalQuantity\Mass;
use Symfony\Component\Security\Core\User\UserInterface as BaseUserInterface;

interface UserInterface extends BaseUserInterface
{
    public function getEmail(): ?string;
}
