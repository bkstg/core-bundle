<?php

namespace Bkstg\CoreBundle\Tests\Controller;

use PHPUnit\Framework\TestCase;
use Sonata\MediaBundle\Provider\Pool;
use Sonata\MediaBundle\Provider\MediaProviderInterface;
use Sonata\MediaBundle\Security\DownloadStrategyInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Bkstg\CoreBundle\Controller\MediaController;
use Bkstg\CoreBundle\Entity\Media;

class MediaControllerTest extends TestCase
{
    // DIC variables.
    private $pool;
    private $em;
    private $auth;

    // Helper variables.
    private $media;
    private $repo;

    private $media_name = 'path.jpg';
    private $provider_name = 'provider_name';
    private $download_mode = 'dl_mode';
    private $format = 'thumb_format';

    /**
     * Set up the controller DIC args.
     *
     * @return void
     */
    public function setUp()
    {
        // The stub media object.
        $this->media = $this->prophesize(Media::class);
        $this->media->getName()->willReturn($this->media_name);
        $this->media->getProviderName()->willReturn($this->provider_name);

        // Create a response.
        $response = $this->prophesize(Response::class);
        $headers = $this->prophesize(ParameterBag::class);
        $headers->set('content-disposition', sprintf('inline; filename="%s"', $this->media_name));
        $response->reveal()->headers = $headers->reveal();

        // Create a provider.
        $provider = $this->prophesize(MediaProviderInterface::class);
        $provider
            ->getDownloadResponse($this->media->reveal(), $this->format, $this->download_mode)
            ->willReturn($response->reveal());
            $provider
                ->getDownloadResponse($this->media->reveal(), 'reference', $this->download_mode)
                ->willReturn($response->reveal());

        // Create the stub pool.
        $this->pool = $this->prophesize(Pool::class);
        $this->pool->getProvider($this->provider_name)->willReturn($provider->reveal());
        $this->pool->getDownloadMode($this->media->reveal())->willReturn($this->download_mode);

        // Create the stub authorization checker.
        $this->auth = $this->prophesize(AuthorizationCheckerInterface::class);

        // Create the stub repo.
        $this->repo = $this->prophesize(EntityRepository::class);

        // Create the stub entity repository.
        $this->em = $this->prophesize(EntityManagerInterface::class);
        $this->em->getRepository(Media::class)->willReturn($this->repo->reveal());

        // The controller.
        $this->controller = new MediaController($this->pool->reveal(), $this->em->reveal(), $this->auth->reveal());
    }

    /**
     * Test a functional serve action.
     *
     * @return void
     */
    public function testServeThumbAction()
    {
        // Configure DIC service stubs.
        $this->repo->findOneBy(['id' => '1'])->willReturn($this->media->reveal())->shouldBeCalled();
        $this->auth->isGranted('view', $this->media->reveal())->willReturn(true);

        // Run through and get the mock response.
        $response = $this->controller->serveAction('test/path_1_thumb_format.jpg');
        $this->assertInstanceOf(Response::class, $response);
    }

    /**
     * Test a functional serve reference action.
     *
     * @return void
     */
    public function testServeReferenceAction()
    {
        // Configure DIC service stubs.
        $this->repo
            ->findOneBy(['providerReference' => 'path.jpg'])
            ->willReturn($this->media->reveal())
            ->shouldBeCalled();
        $this->auth->isGranted('view', $this->media->reveal())->willReturn(true);

        $response = $this->controller->serveAction('test/path.jpg');
        $this->assertInstanceOf(Response::class, $response);
    }

    /**
     * Test a media not found action.
     *
     * @return void
     */
    public function testServeNotFoundAction()
    {
        // Configure DIC service stubs.
        $this->repo->findOneBy(['id' => '1'])->willReturn(null)->shouldBeCalled();

        // Run through and get the mock response.
        $this->expectException(NotFoundHttpException::class);
        $response = $this->controller->serveAction('test/path_1_thumb_format.jpg');
    }

    /**
     * Test a media not allowed action.
     *
     * @return void
     */
    public function testServeAccessDeniedAction()
    {
        // Configure DIC service stubs.
        $this->repo->findOneBy(['id' => '1'])->willReturn($this->media->reveal())->shouldBeCalled();
        $this->auth->isGranted('view', $this->media->reveal())->willReturn(false);

        // Run through and get the mock response.
        $this->expectException(AccessDeniedException::class);
        $response = $this->controller->serveAction('test/path_1_thumb_format.jpg');
    }
}
