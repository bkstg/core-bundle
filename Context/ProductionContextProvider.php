<?php

declare(strict_types=1);

/*
 * This file is part of the BkstgCoreBundle package.
 * (c) Luke Bainbridge <http://www.lukebainbridge.ca/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Bkstg\CoreBundle\Context;

use Bkstg\CoreBundle\Entity\Production;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class ProductionContextProvider implements ProductionContextProviderInterface
{
    private $request_stack;
    private $em;

    /**
     * Create a new production context provider.
     *
     * @param RequestStack           $request_stack The request stack.
     * @param EntityManagerInterface $em            The entity manager service.
     */
    public function __construct(
        RequestStack $request_stack,
        EntityManagerInterface $em
    ) {
        $this->request_stack = $request_stack;
        $this->em = $em;
    }

    /**
     * {@inheritdoc}
     *
     * @return ?Production A production from context or null.
     */
    public function getContext(): ?Production
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
