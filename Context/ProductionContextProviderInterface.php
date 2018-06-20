<?php

namespace Bkstg\CoreBundle\Context;

use Bkstg\CoreBundle\Entity\Production;

interface ProductionContextProviderInterface
{
    /**
     * The purpose of this function is to return a specific production.
     *
     * @return ?Production
     */
    public function getContext(): ?Production;
}
