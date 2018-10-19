<?php

namespace Bkstg\CoreBundle\Tests\CDN;

use Aws\CommandInterface;
use Aws\S3\S3ClientInterface;
use PHPUnit\Framework\TestCase;
use Bkstg\CoreBundle\CDN\PrivateDigitalOceanSpaces;
use Psr\Http\Message\RequestInterface;

class PrivateDigitalOceanSpacesTest extends TestCase
{
    /**
     * Test that we get a correct pre-signed request.
     * @return void
     */
    public function testGetPresignedPath()
    {
        $bucket = 'test-bucket';
        $key = 'test-object';

        // Mock the request.
        $request = $this->createMock(RequestInterface::class);
        $request
            ->expects($this->once())
            ->method('getUri')
            ->willReturn('test-url');

        // Mock the command.
        $get_command = $this->createMock(CommandInterface::class);

        // Mock the client and methods.
        $client = $this->createMock(S3ClientInterface::class);
        $client
            ->expects($this->once())
            ->method('getCommand')
            ->with('GetObject', [
                'Bucket' => $bucket,
                'Key' => $key,
            ])
            ->willReturn($get_command);
        $client
            ->expects($this->once())
            ->method('createPresignedRequest')
            ->with($get_command, '+20 minutes')
            ->willReturn($request);

        // Create and test the spaces.
        $do_cdn = new PrivateDigitalOceanSpaces($bucket, $client);
        $this->assertEquals('test-url', $do_cdn->getPath($key, false));

        // Call empty functions for code coverage.
        $do_cdn->flush($key);
        $do_cdn->flushByString($key);
        $do_cdn->flushPaths([$key]);
        $do_cdn->getFlushStatus($key);
    }
}
