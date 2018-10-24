<?php

declare(strict_types=1);

/*
 * This file is part of the BkstgCoreBundle package.
 * (c) Luke Bainbridge <http://www.lukebainbridge.ca/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Bkstg\CoreBundle\Tests\CDN;

use Aws\CommandInterface;
use Aws\S3\S3ClientInterface;
use Bkstg\CoreBundle\CDN\PrivateDigitalOceanSpaces;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;

class PrivateDigitalOceanSpacesTest extends TestCase
{
    /**
     * Test that we get a correct pre-signed request.
     *
     * @return void
     */
    public function testGetPresignedPath(): void
    {
        $bucket = 'test-bucket';
        $key = 'test-object';

        // Mock the request.
        $request = $this->prophesize(RequestInterface::class);
        $request
            ->getUri()
            ->willReturn('test-url');

        // Mock the command.
        $get_command = $this->prophesize(CommandInterface::class);

        // Mock the client and methods.
        $client = $this->prophesize(S3ClientInterface::class);
        $client
            ->getCommand('GetObject', ['Bucket' => $bucket, 'Key' => $key])
            ->willReturn($get_command->reveal());
        $client
            ->createPresignedRequest($get_command, '+20 minutes')
            ->willReturn($request->reveal());

        // Create and test the spaces.
        $do_cdn = new PrivateDigitalOceanSpaces($bucket, $client->reveal());
        $this->assertEquals('test-url', $do_cdn->getPath($key, false));

        // Call empty functions for code coverage.
        $do_cdn->flush($key);
        $do_cdn->flushByString($key);
        $do_cdn->flushPaths([$key]);
        $do_cdn->getFlushStatus($key);
    }
}
