<?php

namespace Bkstg\CoreBundle\Context;

interface ContextProviderInterface
{
    /**
     * The purpose of this function is to return a specific "context".
     *
     * A context can be anything, it will depend on the provider and what is
     * needed.  The context provider should determine said context based on
     * whatever services are needed and return it.
     */
    public function getContext();
}
