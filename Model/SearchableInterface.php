<?php

namespace Bkstg\SearchBundle\Model;

use MidnightLuke\GroupSecurityBundle\Model\GroupableInterface;

interface SearchableInterface extends GroupableInterface
{
    const STATUS_ACTIVE = 1;
    const STATUS_CLOSED = 0;

    /**
     * Get the status, this is required to search an entity.
     *
     * @return integer
     */
    public function getStatus();
}
