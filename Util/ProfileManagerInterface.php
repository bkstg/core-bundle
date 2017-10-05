<?php

namespace Bkstg\CoreBundle\Util;

interface ProfileManagerInterface
{
    public function findAllBlocked();
    public function findAllEnabled();
}
