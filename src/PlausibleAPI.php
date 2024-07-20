<?php

namespace Devarts\PlausiblePHP;

use Devarts\PlausiblePHP\Api\EventsApi;
use Devarts\PlausiblePHP\Api\SitesApi;
use Devarts\PlausiblePHP\Api\StatsApi;
use GuzzleHttp\Client;

class PlausibleAPI
{
    protected Client $client;

    public function __construct(string $token, string $base_uri = 'https://plausible.io/api/')
    {
        // This provides backwards compatibility with the $base_uri in earlier
        // versions of this library, which assumed a trailing "/v1/" in the path.
        if (substr($base_uri, -4) === '/v1/' || substr($base_uri, -3) === '/v1') {
            $base_uri = substr($base_uri, 0, -3);
        }

        $this->client = new Client([
            'base_uri' => $base_uri,
            'headers' => [
                'Authorization' => sprintf('Bearer %s', $token),
            ],
            'http_errors' => true,
        ]);
    }

    /**
     * Returns the HTTP client used to make API requests.
     *
     * This is primarily useful for testing purposes.
     */
    public function getClient(): Client
    {
        return $this->client;
    }

    /**
     * Overrides the client created in the constructor with a custom client.
     *
     * This is primarily useful for testing purposes.
     */
    public function setClient(Client $client): void
    {
        $this->client = $client;
    }

    public function stats(): StatsApi
    {
        return new StatsApi($this->client);
    }

    public function events(): EventsApi
    {
        return new EventsApi($this->client);
    }

    public function sites(): SitesApi
    {
        return new SitesApi($this->client);
    }
}
