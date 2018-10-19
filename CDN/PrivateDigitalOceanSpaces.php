<?php

declare(strict_types=1);

/*
 * This file is part of the BkstgCoreBundle package.
 * (c) Luke Bainbridge <http://www.lukebainbridge.ca/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Bkstg\CoreBundle\CDN;

use Aws\S3\S3Client;
use Sonata\MediaBundle\CDN\CDNInterface;

class PrivateDigitalOceanSpaces implements CDNInterface
{
    private $bucket;
    private $client;

    /**
     * Create a new digital ocean CDN.
     *
     * @param string   $bucket The bucket for this CDN.
     * @param S3Client $client The S3 client service.
     */
    public function __construct(string $bucket, S3Client $client)
    {
        $this->bucket = $bucket;
        $this->client = $client;
    }

    /**
     * Return the base path.
     *
     * @param string $key         The key for this item.
     * @param bool   $isFlushable Whether or not this is flushable.
     *
     * @return string
     */
    public function getPath($key, $isFlushable)
    {
        $cmd = $this->client->getCommand('GetObject', [
            'Bucket' => $this->bucket,
            'Key' => $key,
        ]);

        $request = $this->client->createPresignedRequest($cmd, '+20 minutes');

        return (string) $request->getUri();
    }

    /**
     * {@inheritdoc}
     *
     * @param string $string The path to flush.
     *
     * @return void
     */
    public function flush($string)
    {
    }

    /**
     * {@inheritdoc}
     *
     * @param string $string The path to flush.
     *
     * @return void
     */
    public function flushByString($string)
    {
    }

    /**
     * {@inheritdoc}
     *
     * @param array $paths The paths to flush.
     *
     * @return void
     */
    public function flushPaths(array $paths)
    {
    }

    /**
     * {@inheritdoc}
     *
     * @param string $identifier The path to flush.
     *
     * @return void
     */
    public function getFlushStatus($identifier)
    {
    }
}
