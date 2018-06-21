<?php

namespace Bkstg\CoreBundle\Model;

interface PublishableInterface
{
    /**
     * Should return true if the entity is active.
     *
     * @return boolean
     */
    public function isActive(): bool;

    /**
     * Should return true if the entity is published.
     *
     * @return boolean
     */
    public function isPublished(): bool;

    /**
     * Allows the published flag to be set.
     *
     * @param boolean $published The value of published.
     * @return void
     */
    public function setPublished(bool $published);
}
