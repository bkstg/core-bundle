<?php

declare(strict_types=1);

/*
 * This file is part of the BkstgCoreBundle package.
 * (c) Luke Bainbridge <http://www.lukebainbridge.ca/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Bkstg\CoreBundle\Tests\Context;

use Bkstg\CoreBundle\Context\ProductionContextProvider;
use Bkstg\CoreBundle\Entity\Production;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class ProductionContextProviderTest extends TestCase
{
    /**
     * Test that production context returns a production.
     *
     * @return void
     */
    public function testProductionContext(): void
    {
        // Create the attributes stub.
        $attributes = $this->prophesize(ParameterBag::class);
        $attributes->has('production_slug')->willReturn(true);
        $attributes->get('production_slug')->willReturn('test-production');

        // Create the request stub.
        $request = $this->prophesize(Request::class);
        $request->reveal()->attributes = $attributes->reveal();

        // Create mock request matcher stub.
        $request_stack = $this->prophesize(RequestStack::class);
        $request_stack->getCurrentRequest()->willReturn($request->reveal());

        // Create repo and entity manager stubs.
        $repo = $this->prophesize(EntityRepository::class);
        $repo->findOneBy(['slug' => 'test-production'])->willReturn(new Production());
        $em = $this->prophesize(EntityManagerInterface::class);
        $em->getRepository(Production::class)->willReturn($repo->reveal());

        // Create and test the context provider.
        $provider = new ProductionContextProvider($request_stack->reveal(), $em->reveal());
        $this->assertInstanceOf(Production::class, $provider->getContext());
    }

    /**
     * Test that no production slug returns null.
     *
     * @return void
     */
    public function testNoProductionContext(): void
    {
        // Create the attributes stub.
        $attributes = $this->prophesize(ParameterBag::class);
        $attributes->has('production_slug')->willReturn(false);

        // Create the request stub.
        $request = $this->prophesize(Request::class);
        $request->reveal()->attributes = $attributes->reveal();

        // Create request matcher stub.
        $request_stack = $this->prophesize(RequestStack::class);
        $request_stack->getCurrentRequest()->willReturn($request->reveal());

        // Create entity manager dummy.
        $em = $this->prophesize(EntityManagerInterface::class);

        // Create and test the context provider.
        $provider = new ProductionContextProvider($request_stack->reveal(), $em->reveal());
        $this->assertNull($provider->getContext());
    }

    /**
     * Test that not found production context returns null.
     *
     * @return void
     */
    public function testProductionContextNotFound(): void
    {
        // Create the attributes stub.
        $attributes = $this->prophesize(ParameterBag::class);
        $attributes->has('production_slug')->willReturn(true);
        $attributes->get('production_slug')->willReturn('test-production');

        // Create the request stub.
        $request = $this->prophesize(Request::class);
        $request->reveal()->attributes = $attributes->reveal();

        // Create mock request matcher stub.
        $request_stack = $this->prophesize(RequestStack::class);
        $request_stack->getCurrentRequest()->willReturn($request->reveal());

        // Create repo and entity manager stubs.
        $repo = $this->prophesize(EntityRepository::class);
        $repo->findOneBy(['slug' => 'test-production'])->willReturn(null);
        $em = $this->prophesize(EntityManagerInterface::class);
        $em->getRepository(Production::class)->willReturn($repo->reveal());

        // Create and test the context provider.
        $provider = new ProductionContextProvider($request_stack->reveal(), $em->reveal());
        $this->assertNull($provider->getContext());
    }
}
