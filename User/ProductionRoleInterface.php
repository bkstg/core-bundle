<?php

namespace Bkstg\CoreBundle\User;

interface ProductionRoleInterface
{
    /**
     * Get the designation of the role (ie "Cast" or "Crew").
     *
     * @return string The role's designation.
     */
    public function getDesignation();

    /**
     * Get the name of the role (ie "Romeo").
     *
     * @return string The role's name.
     */
    public function getName();
}
