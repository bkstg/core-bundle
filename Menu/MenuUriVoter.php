<?php

namespace Bkstg\CoreBundle\Menu;

use Knp\Menu\ItemInterface;
use Knp\Menu\Matcher\Voter\VoterInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class MenuUriVoter implements VoterInterface
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
