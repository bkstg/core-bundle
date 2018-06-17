<?php

namespace Bkstg\CoreBundle\Model;

interface PublishableInterface
{
    public function isActive(): bool;
    public function isPublished(): bool;
    public function setPublished(bool $published);
}
