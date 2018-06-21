<?php

namespace Bkstg\CoreBundle\Menu\Matcher;

use Knp\Menu\ItemInterface;
use Knp\Menu\Matcher\Matcher;
use Symfony\Component\HttpFoundation\RequestStack;

class PathAncestorMatcher extends Matcher
{
    private $request_stack;

    /**
     * Build a new path ancestor matcher.
     *
     * @param array        $voters        The voters for this service.
     * @param RequestStack $request_stack The request stack service.
     */
    public function __construct(array $voters, RequestStack $request_stack)
    {
        $this->request_stack = $request_stack;
        parent::__construct($voters);
    }

    /**
     * Overrides parent isAncestor to determine based on path.
     *
     * @param  ItemInterface $item  The item to check.
     * @param  mixed         $depth The depth to search.
     * @return boolean
     */
    public function isAncestor(ItemInterface $item, $depth = null): bool
    {
        $request = $this->request_stack->getCurrentRequest();
        return strpos($request->getPathInfo(), $item->getUri()) === 0;
    }
}
