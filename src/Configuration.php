<?php

namespace Devarts\PlausiblePHP;

use GuzzleHttp\Client;

class Configuration
{
    private string $base_uri;
    private Client $client;

    private function __construct(string $api_key, string $base_uri)
    {
        // This provides backwards compatibility with the $base_uri in earlier
        // versions of this library, which assumed a trailing "/v1/" in the path.
        if (substr($base_uri, -4) === '/v1/' || substr($base_uri, -3) === '/v1') {
            $base_uri = substr($base_uri, 0, -3);
        }

        $this->client = new Client([
            'base_uri' => $base_uri,
            'headers' => [
                'Authorization' => sprintf('Bearer %s', $api_key),
            ],
            'http_errors' => true,
        ]);

        $this->base_uri = $base_uri;
    }

    public static function create(string $api_key, string $base_uri = 'https://plausible.io/api/'): self
    {
        return new self($api_key, $base_uri);
    }

    public function getClient(): Client
    {
        return $this->client;
    }

    public function getBaseUri(): string
    {
        return $this->base_uri;
    }
}