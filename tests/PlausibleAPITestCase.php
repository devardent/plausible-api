<?php

namespace Devarts\PlausiblePHP\Test;

use Devarts\PlausiblePHP\Configuration;
use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class PlausibleAPITestCase extends TestCase
{
    protected function mockResponse(string $expected_response): ResponseInterface
    {
        $stream = $this->createStub(StreamInterface::class);
        $stream->method('getContents')->willReturn($expected_response);

        $response = $this->createStub(ResponseInterface::class);
        $response->method('getBody')->willReturn($stream);

        return $response;
    }

    protected function mockConfiguration(): Configuration
    {
        $configuration = $this->createMock(Configuration::class);

        $client = $this->createMock(Client::class);

        $configuration
            ->expects($this->any())
            ->method('getClient')
            ->willReturn($client);

        return $configuration;
    }
}