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
        // Create the request mock.
        $request = $this->createMock(Request::class);
        $request->attributes = $this->createMock(ParameterBag::class);
        $request->attributes
            ->expects($this->once())
            ->method('has')
            ->with('production_slug')
            ->willReturn(true);
        $request->attributes
            ->expects($this->once())
            ->method('get')
            ->with('production_slug')
            ->willReturn('test-production');

        // Create mock request matcher mock.
        $request_stack = $this->createMock(RequestStack::class);
        $request_stack
            ->expects($this->once())
            ->method('getCurrentRequest')
            ->willReturn($request);

        // Create repo and entity manager mock.
        $repo = $this->createMock(EntityRepository::class);
        $repo
            ->expects($this->once())
            ->method('findOneBy')
            ->with(['slug' => 'test-production'])
            ->willReturn(new Production());
        $em = $this->createMock(EntityManagerInterface::class);
        $em
            ->expects($this->once())
            ->method('getRepository')
            ->willReturn($repo);

        // Create and test the context provider.
        $provider = new ProductionContextProvider($request_stack, $em);
        $this->assertInstanceOf(Production::class, $provider->getContext());
    }

    /**
     * Test that no production slug returns null.
     *
     * @return void
     */
    public function testNoProductionContext(): void
    {
        // Create the request mock.
        $request = $this->createMock(Request::class);
        $request->attributes = $this->createMock(ParameterBag::class);
        $request->attributes
            ->expects($this->once())
            ->method('has')
            ->with('production_slug')
            ->willReturn(false);

        // Create mock request matcher mock.
        $request_stack = $this->createMock(RequestStack::class);
        $request_stack
            ->expects($this->once())
            ->method('getCurrentRequest')
            ->willReturn($request);

        // Create repo and entity manager mock.
        $em = $this->createMock(EntityManagerInterface::class);

        // Create and test the context provider.
        $provider = new ProductionContextProvider($request_stack, $em);
        $this->assertNull($provider->getContext());
    }

    /**
     * Test that not found production context returns null.
     *
     * @return void
     */
    public function testProductionContextNotFound(): void
    {
        // Create the request mock.
        $request = $this->createMock(Request::class);
        $request->attributes = $this->createMock(ParameterBag::class);
        $request->attributes
            ->expects($this->once())
            ->method('has')
            ->with('production_slug')
            ->willReturn(true);
        $request->attributes
            ->expects($this->once())
            ->method('get')
            ->with('production_slug')
            ->willReturn('test-production');

        // Create mock request matcher mock.
        $request_stack = $this->createMock(RequestStack::class);
        $request_stack
            ->expects($this->once())
            ->method('getCurrentRequest')
            ->willReturn($request);

        // Create repo and entity manager mock.
        $repo = $this->createMock(EntityRepository::class);
        $repo
            ->expects($this->once())
            ->method('findOneBy')
            ->with(['slug' => 'test-production'])
            ->willReturn(null);
        $em = $this->createMock(EntityManagerInterface::class);
        $em
            ->expects($this->once())
            ->method('getRepository')
            ->willReturn($repo);

        // Create and test the context provider.
        $provider = new ProductionContextProvider($request_stack, $em);
        $this->assertNull($provider->getContext());
    }
}
