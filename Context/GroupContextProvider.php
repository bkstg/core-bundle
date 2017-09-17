<?php

namespace Bkstg\CoreBundle\Context;

use Bkstg\CoreBundle\Context\ContextProviderInterface;
use Bkstg\CoreBundle\Model\Production;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class GroupContextProvider implements ContextProviderInterface
{
    private $request_stack;
    private $em;

    public function __construct(
        RequestStack $request_stack,
        EntityManagerInterface $em
    ) {
        $this->request_stack = $request_stack;
        $this->em = $em;
    }

    /**
     * {@inheritdoc}
     */
    public function getContext()
    {
        $request = $this->request_stack->getCurrentRequest();
        if (!$request->attributes->has('slug')) {
            return null;
        }

        $slug = $request->attributes->get('slug');
        if (null !== $production = $this->em->getRepository(Production::class)->findOneBy(['slug' => $slug])) {
            return $production;
        }

        return null;
    }
}
