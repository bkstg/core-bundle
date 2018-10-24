<?php

namespace Bkstg\CoreBundle\Tests\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ParameterBag;
use Knp\Component\Pager\PaginatorInterface;
use Bkstg\CoreBundle\Repository\ProductionRepository;
use Bkstg\CoreBundle\Entity\Production;
use Bkstg\CoreBundle\Controller\ProductionAdminController;
use Doctrine\ORM\AbstractQuery;

class ProductionAdminControllerTest extends ControllerTest
{
    /**
     * Set up this test.
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->controller = new ProductionAdminController(
            $this->templating->reveal(),
            $this->session->reveal(),
            $this->form->reveal(),
            $this->em->reveal(),
            $this->translator->reveal(),
            $this->url_generator->reveal()
        );
    }

    /**
     * Test the index action.
     *
     * @return void
     */
    public function testIndexAction()
    {
        // Create the dummy query.
        $query = $this->prophesize(ParameterBag::class);
        $query->getInt('page', 1)->willReturn(1);

        // Create stub request and query.
        $request = $this->prophesize(Request::class);
        $request->reveal()->query = $query->reveal();

        // Create stub repository.
        $repo = $this->prophesize(ProductionRepository::class);
        $repo->findAllOpenQuery()->willReturn($this->prophesize(AbstractQuery::class));

        // Attach repo to em.
        $this->em->getRepository(Production::class)->willReturn($repo);

        // Create stub paginator.
        $paginator = $this->getMockPaginator();

        // Get the response.
        $response = $this->controller->indexAction($request->reveal(), $paginator->reveal());
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * Test the archive action.
     *
     * @return void
     */
    public function testArchiveAction()
    {
        // Create the dummy query.
        $query = $this->prophesize(ParameterBag::class);
        $query->getInt('page', 1)->willReturn(1);

        // Create stub request and query.
        $request = $this->prophesize(Request::class);
        $request->reveal()->query = $query->reveal();

        // Create stub repository.
        $repo = $this->prophesize(ProductionRepository::class);
        $repo->findAllClosedQuery()->willReturn($this->prophesize(AbstractQuery::class));

        // Attach repo to em.
        $this->em->getRepository(Production::class)->willReturn($repo);

        // Create stub paginator.
        $paginator = $this->getMockPaginator();

        // Get the response.
        $response = $this->controller->archiveAction($request->reveal(), $paginator->reveal());
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }
}
