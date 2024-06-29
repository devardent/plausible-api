<?php

namespace Devarts\PlausiblePHP\Test;

use Devarts\PlausiblePHP\PlausibleAPI;
use PHPUnit\Framework\TestCase;
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
}
