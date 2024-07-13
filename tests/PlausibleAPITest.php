<?php

namespace Devarts\PlausiblePHP\Test;

use Devarts\PlausiblePHP\PlausibleAPI;
use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;

class PlausibleAPITest extends TestCase
{
    /**
     * @testWith ["https://plausible.io/api/", null]
     *           ["https://plausible.io/api/", "https://plausible.io/api/v1/"]
     *           ["https://plausible.io/api", "https://plausible.io/api/v1"]
     *           ["https://example.com/path/to/plausible/", "https://example.com/path/to/plausible/v1/"]
     *           ["https://example.com/path/to/plausible", "https://example.com/path/to/plausible/v1"]
     */
    public function testBaseUri(string $expected_base_uri, ?string $passed_base_uri): void
    {
        if ($passed_base_uri !== null) {
            $plausible = new PlausibleAPI('an_api_token', $passed_base_uri);
        } else {
            $plausible = new PlausibleAPI('an_api_token');
        }

        /** @var UriInterface $baseUri */
        $baseUri = $plausible->getClient()->getConfig('base_uri');

        $this->assertSame($expected_base_uri, (string) $baseUri);
    }

    public function testRecordEvent(): void
    {
        $response = $this->createStub(ResponseInterface::class);
        $response->method('getStatusCode')->willReturn(202);

        $client = $this->createMock(Client::class);
        $client
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

        $plausible = new PlausibleAPI('an_api_token');
        $plausible->setClient($client);

        $this->assertTrue($plausible->recordEvent(
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

    public function testRecordEventExcludingOptionalValues(): void
    {
        $response = $this->createStub(ResponseInterface::class);
        $response->method('getStatusCode')->willReturn(202);

        $client = $this->createMock(Client::class);
        $client
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

        $plausible = new PlausibleAPI('an_api_token');
        $plausible->setClient($client);

        $this->assertTrue($plausible->recordEvent(
            'subdomain.example.com',
            'custom.event',
            'https://example.com/path/to/some/page',
            'MyOtherAgent/3.0',
            '77.91.71.175',
        ));
    }

    public function testRecordEventReturnsFalseWhenUnsuccessful(): void
    {
        $response = $this->createStub(ResponseInterface::class);
        $response->method('getStatusCode')->willReturn(400);

        $client = $this->createStub(Client::class);
        $client->method('post')->willReturn($response);

        $plausible = new PlausibleAPI('an_api_token');
        $plausible->setClient($client);

        $this->assertFalse($plausible->recordEvent(
            'subdomain2.example.com',
            'something-happened',
            'https://example.com/path/to/page',
            'MyAgent/2.0',
            '4.243.144.9',
        ));
    }
}
