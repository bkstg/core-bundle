<?php

namespace Bkstg\CoreBundle\Context;

use Bkstg\CoreBundle\Context\ProductionContextProviderInterface;
use Bkstg\CoreBundle\Entity\Production;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class ProductionContextProvider implements ProductionContextProviderInterface
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
        if (!$request->attributes->has('production_slug')) {
            return null;
        }

        $slug = $request->attributes->get('production_slug');
        if (null !== $production = $this->em->getRepository(Production::class)->findOneBy(['slug' => $slug])) {
            return $production;
        }

        return null;
    }
}
