<?php

namespace Devarts\PlausiblePHP;

use GuzzleHttp\Client;
use Devarts\PlausiblePHP\Response\AggregatedMetrics;
use Devarts\PlausiblePHP\Response\BreakdownCollection;
use Devarts\PlausiblePHP\Response\Goal;
use Devarts\PlausiblePHP\Response\SharedLink;
use Devarts\PlausiblePHP\Response\TimeseriesCollection;
use Devarts\PlausiblePHP\Response\Website;

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

    public function getRealtimeVisitors(string $site_id): int
    {
        $response = $this->client->get('v1/stats/realtime/visitors', [
            'query' => [
                'site_id' => $site_id,
            ],
        ]);

        return (int) $response->getBody()->getContents();
    }

    public function getAggregate(string $site_id, array $extras = []): AggregatedMetrics
    {
        $response = $this->client->get('v1/stats/aggregate', [
            'query' => array_merge(
                $extras,
                [
                    'site_id' => $site_id,
                ]
            ),
        ]);

        return AggregatedMetrics::fromApiResponse($response->getBody()->getContents());
    }

    public function getTimeseries(string $site_id, array $extras = []): TimeseriesCollection
    {
        $response = $this->client->get('v1/stats/timeseries', [
            'query' => array_merge(
                $extras,
                [
                    'site_id' => $site_id,
                ]
            ),
        ]);

        return TimeseriesCollection::fromApiResponse($response->getBody()->getContents());
    }

    public function getBreakdown(string $site_id, string $property, array $extras = []): BreakdownCollection
    {
        $response = $this->client->get('v1/stats/breakdown', [
            'query' => array_merge(
                $extras,
                [
                    'site_id' => $site_id,
                    'property' => $property,
                ]
            ),
        ]);

        return BreakdownCollection::fromApiResponse($response->getBody()->getContents());
    }

    public function createWebsite(array $payload): Website
    {
        $response = $this->client->post('v1/sites', [
            'form_params' => $payload,
        ]);

        return Website::fromApiResponse($response->getBody()->getContents());
    }

    public function updateWebsite(string $site_id, array $payload): Website
    {
        $response = $this->client->put('v1/sites/' . urlencode($site_id), [
            'form_params' => $payload,
        ]);

        return Website::fromApiResponse($response->getBody()->getContents());
    }

    public function deleteWebsite(string $site_id): bool
    {
        $response = $this->client->delete('v1/sites/' . urlencode($site_id));

        return json_decode($response->getBody()->getContents(), true)['deleted'];
    }

    public function getWebsite(string $site_id): Website
    {
        $response = $this->client->get('v1/sites/' . urlencode($site_id));

        return Website::fromApiResponse($response->getBody()->getContents());
    }

    public function createSharedLink(array $payload): SharedLink
    {
        $response = $this->client->put('v1/sites/shared-links', [
            'form_params' => $payload,
        ]);

        return SharedLink::fromApiResponse($response->getBody()->getContents());
    }

    public function createGoal(array $payload): Goal
    {
        $response = $this->client->put('v1/sites/goals', [
            'form_params' => $payload,
        ]);

        return Goal::fromApiResponse($response->getBody()->getContents());
    }

    public function deleteGoal(int $goal_id, string $site_id): bool
    {
        $response = $this->client->delete('v1/sites/goals/' . urlencode((string) $goal_id), [
            'form_params' => [
                'site_id' => $site_id,
            ],
        ]);

        return json_decode($response->getBody()->getContents(), true)['deleted'];
    }
}