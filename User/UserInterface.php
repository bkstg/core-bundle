<?php

namespace Bkstg\CoreBundle\User;

use Symfony\Component\Security\Core\User\AdvancedUserInterface;

interface UserInterface extends AdvancedUserInterface
{
    public function getProfile();
    public function getId();
}
