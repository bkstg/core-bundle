<?php  declare(strict_types=1);

namespace Bkstg\CoreBundle\User;

interface ProductionRoleInterface
{
    /**
     * Get the designation of the role (ie "Cast" or "Crew").
     *
     * @return string The role's designation.
     */
    public function getDesignation(): ?string;

    /**
     * Get the name of the role (ie "Romeo").
     *
     * @return string The role's name.
     */
    public function getName(): ?string;
}
