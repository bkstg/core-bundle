<?php

namespace Bkstg\CoreBundle\Menu\Voter;

use Knp\Menu\ItemInterface;
use Knp\Menu\Matcher\Voter\VoterInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class PathAncestorVoter implements VoterInterface
{
    private $request_stack;

    public function __construct(RequestStack $request_stack)
    {
        $this->request_stack = $request_stack;
    }

    /**
     * {@inheritdoc}
     */
    public function matchItem(ItemInterface $item)
    {
        $request = $this->request_stack->getCurrentRequest();
        dump('Item URI:' . $item->getUri() . ' Path: ' . $request->getPathInfo());
        dump(strpos($request->getPathInfo(), $item->getUri()));
        return strpos($request->getPathInfo(), $item->getUri()) === 0;
    }
}
