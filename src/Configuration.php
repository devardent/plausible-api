<?php

namespace Devarts\PlausiblePHP;

use GuzzleHttp\Client;

class Configuration
{
    private string $base_uri;
    private Client $client;

    private function __construct(string $api_key, string $base_uri)
    {
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