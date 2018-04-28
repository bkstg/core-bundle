<?php

namespace Bkstg\CoreBundle\Context;

interface ProductionContextProviderInterface
{
    /**
     * The purpose of this function is to return a specific production.
     *
     * @return Production
     */
    public function getContext();
}
