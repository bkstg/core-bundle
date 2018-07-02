<?php

declare(strict_types=1);

/*
 * This file is part of the BkstgCoreBundle package.
 * (c) Luke Bainbridge <http://www.lukebainbridge.ca/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
     * @param ItemInterface $item  The item to check.
     * @param mixed         $depth The depth to search.
     *
     * @return bool
     */
    public function isAncestor(ItemInterface $item, $depth = null): bool
    {
        $request = $this->request_stack->getCurrentRequest();

        return 0 === strpos($request->getPathInfo(), $item->getUri());
    }
}
