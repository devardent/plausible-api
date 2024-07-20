<?php

namespace Devarts\PlausiblePHP\Test;

use Devarts\PlausiblePHP\Configuration;
use Devarts\PlausiblePHP\PlausibleAPI;
use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;

class PlausibleEventsAPITest extends TestCase
{
    /**
     * @test
     * @testWith ["https://plausible.io/api/", null]
     *           ["https://plausible.io/api/", "https://plausible.io/api/v1/"]
     *           ["https://plausible.io/api", "https://plausible.io/api/v1"]
     *           ["https://example.com/path/to/plausible/", "https://example.com/path/to/plausible/v1/"]
     *           ["https://example.com/path/to/plausible", "https://example.com/path/to/plausible/v1"]
     */
    public function it_should_return_base_uri(string $expected_base_uri, ?string $passed_base_uri): void
    {
        if ($passed_base_uri !== null) {
            $plausible = new PlausibleAPI(Configuration::create('an_api_token', $passed_base_uri));
        } else {
            $plausible = new PlausibleAPI(Configuration::create('an_api_token'));
        }

        /** @var UriInterface $baseUri */
        $baseUri = $plausible->getConfiguration()->getBaseUri();

        $this->assertSame($expected_base_uri, (string) $baseUri);
    }

    /**
     * @test
     */
    public function it_should_record_event(): void
    {
        $response = $this->createStub(ResponseInterface::class);
        $response->method('getStatusCode')->willReturn(202);

        $configuration = $this->mockConfiguration();

        $configuration
            ->getClient()
            ->expects($this->once())
            ->method('post')
            ->with(
                $this->equalTo('event'),
                $this->equalTo([
                    'headers' => [
                        'user-agent' => 'MyAgent/3.0',
                        'x-forwarded-for' => '96.44.96.255',
                        'content-type' => 'application/json',
                    ],
                    'body' => json_encode([
                        'domain' => 'example.com',
                        'name' => 'pageview',
                        'url' => 'https://example.com/path/to/some/page',
                        'referrer' => 'https://example.com/path/to/another/page',
                        'props' => [
                            'logged_in' => false,
                            'language' => 'en-US',
                        ],
                        'revenue' => [
                            'currency' => 'USD',
                            'amount' => 315.42,
                        ],
                    ])
                ])
            )->willReturn($response);

        $plausible = new PlausibleAPI($configuration);

        $this->assertTrue($plausible->events()->recordEvent(
            'example.com',
            'pageview',
            'https://example.com/path/to/some/page',
            'MyAgent/3.0',
            '96.44.96.255',
            'https://example.com/path/to/another/page',
            ['logged_in' => false, 'language' => 'en-US'],
            ['currency' => 'USD', 'amount' => 315.42],
        ));
    }

    /**
     * @test
     */
    public function it_should_record_event_excluding_optional_values(): void
    {
        $response = $this->createStub(ResponseInterface::class);
        $response->method('getStatusCode')->willReturn(202);

        $configuration = $this->mockConfiguration();

        $configuration
            ->getClient()
            ->expects($this->once())
            ->method('post')
            ->with(
                $this->equalTo('event'),
                $this->equalTo([
                    'headers' => [
                        'user-agent' => 'MyOtherAgent/3.0',
                        'x-forwarded-for' => '77.91.71.175',
                        'content-type' => 'application/json',
                    ],
                    'body' => json_encode([
                        'domain' => 'subdomain.example.com',
                        'name' => 'custom.event',
                        'url' => 'https://example.com/path/to/some/page',
                    ])
                ])
            )->willReturn($response);

        $plausible = new PlausibleAPI($configuration);

        $this->assertTrue($plausible->events()->recordEvent(
            'subdomain.example.com',
            'custom.event',
            'https://example.com/path/to/some/page',
            'MyOtherAgent/3.0',
            '77.91.71.175',
        ));
    }

    /**
     * @test
     */
    public function it_should_not_record_event_when_request_is_unsuccessful(): void
    {
        $response = $this->createStub(ResponseInterface::class);
        $response->method('getStatusCode')->willReturn(400);

        $configuration = $this->mockConfiguration();

        $configuration
            ->getClient()
            ->expects($this->once())
            ->method('post')
            ->willReturn($response);

        $plausible = new PlausibleAPI($configuration);

        $this->assertFalse($plausible->events()->recordEvent(
            'subdomain2.example.com',
            'something-happened',
            'https://example.com/path/to/page',
            'MyAgent/2.0',
            '4.243.144.9',
        ));
    }

    private function mockConfiguration(): Configuration
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
