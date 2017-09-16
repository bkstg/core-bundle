<?php

namespace Bkstg\CoreBundle\Menu\Voter;

use Knp\Menu\ItemInterface;
use Knp\Menu\Matcher\Voter\VoterInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class MenuRequestUriVoter implements VoterInterface
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
        return $request->getBaseUrl().$request->getPathInfo() == $item->getUri();
    }
}
