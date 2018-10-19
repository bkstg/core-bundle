<?php

declare(strict_types=1);

/*
 * This file is part of the BkstgCoreBundle package.
 * (c) Luke Bainbridge <http://www.lukebainbridge.ca/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Bkstg\CoreBundle\Controller;

use Bkstg\CoreBundle\Entity\Media;
use Doctrine\ORM\EntityManagerInterface;
use Sonata\MediaBundle\Provider\MediaProviderInterface;
use Sonata\MediaBundle\Provider\Pool;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class MediaController
{
    private $pool;
    private $em;
    private $auth;

    /**
     * Create a new media controller.
     *
     * @param Pool                          $pool The media pool service.
     * @param EntityManagerInterface        $em   The entity manager service.
     * @param AuthorizationCheckerInterface $auth The authorization checker service.
     */
    public function __construct(
        Pool $pool,
        EntityManagerInterface $em,
        AuthorizationCheckerInterface $auth
    ) {
        $this->pool = $pool;
        $this->em = $em;
        $this->auth = $auth;
    }

    /**
     * Reverses a media path into a media object and sends as download.
     *
     * @param string $path The media path being requested.
     *
     * @throws NotFoundHttpException If the media is not found.
     * @throws AccessDeniedException If the user does not have access to the media.
     *
     * @return Response
     */
    public function serveAction(string $path): Response
    {
        // Get the media repo.
        $media_repo = $this->em->getRepository(Media::class);

        // Gather some information about the media requested.
        $path_parts = explode('/', $path);
        $filename = array_pop($path_parts);
        $filename_parts = explode('_', $filename);

        // This is a thumb of the original if the filename is not a hash.
        if (count($filename_parts) > 1) {
            // Load the media by id.
            $media = $media_repo->findOneBy(['id' => $filename_parts[1]]);

            // Implode everything after id and strip extension for format.
            $format = implode('_', array_slice($filename_parts, 2));
            $format = substr($format, 0, strrpos($format, '.'));
        } else {
            // Use the reference format and load by filename.
            $media = $media_repo->findOneBy(['providerReference' => $filename]);
            $format = MediaProviderInterface::FORMAT_REFERENCE;
        }

        // Media not found.
        if (null === $media) {
            throw new NotFoundHttpException();
        }

        // Check access for the media.
        if (!$this->auth->isGranted('view', $media)) {
            throw new AccessDeniedException();
        }

        // Use the media pool to get the provider and download response.
        $provider = $this->pool->getProvider($media->getProviderName());
        $response = $provider->getDownloadResponse($media, $format, $this->pool->getDownloadMode($media));

        // This is being sent inline to the browser, prepare it.
        if ($response instanceof BinaryFileResponse) {
            $response->prepare($request);
        }

        // Set the content-disposition and send to the browser.
        $response->headers->set('content-disposition', sprintf('inline; filename="%s"', $media->getName()));

        return $response;
    }
}
