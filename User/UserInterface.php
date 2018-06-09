<?php

namespace Bkstg\CoreBundle\User;

use Bkstg\MediaBundle\Entity\Media;
use PhpUnitsOfMeasure\PhysicalQuantity\Length;
use PhpUnitsOfMeasure\PhysicalQuantity\Mass;
use Symfony\Component\Security\Core\User\UserInterface as BaseUserInterface;

interface UserInterface extends BaseUserInterface
{
    public function getFirstName(): ?string;
    public function getLastName(): ?string;
    public function getEmail(): ?string;
    public function getPhone(): ?string;
    public function getHeight(): ?Length;
    public function getWeight(): ?Mass;
    public function getImage(): ?Media;
    public function getFacebook(): ?string;
    public function getTwitter(): ?string;
}
