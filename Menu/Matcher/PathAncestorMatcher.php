<?php

namespace Bkstg\CoreBundle\Menu\Matcher;

use Knp\Menu\ItemInterface;
use Knp\Menu\Matcher\Matcher;
use Symfony\Component\HttpFoundation\RequestStack;

class PathAncestorMatcher extends Matcher
{
    private $request_stack;

    public function __construct(array $voters, RequestStack $request_stack)
    {
        $this->request_stack = $request_stack;
        parent::__construct($voters);
    }

    public function isAncestor(ItemInterface $item, $depth = null)
    {
        $request = $this->request_stack->getCurrentRequest();
        return strpos($request->getPathInfo(), $item->getUri()) === 0;
    }
}
