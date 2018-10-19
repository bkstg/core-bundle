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

    public function __construct($bucket, S3Client $client)
    {
        $this->bucket = $bucket;
        $this->client = $client;
    }

    /**
     * Return the base path.
     *
     * @param string $key
     * @param bool   $isFlushable
     *
     * @return string
     */
    public function getPath($key, $isFlushable)
    {
        $cmd = $this->client->getCommand('GetObject', [
            'Bucket' => $this->bucket,
            'Key'    => $key
        ]);

        $request = $this->client->createPresignedRequest($cmd, '+20 minutes');
        return (string) $request->getUri();
    }

    /**
     * {@inheritdoc}
     */
    public function flush($string)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function flushByString($string)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function flushPaths(array $paths)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getFlushStatus($identifier)
    {
    }
}
