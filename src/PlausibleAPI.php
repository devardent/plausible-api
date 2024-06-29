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

    /**
     * Overrides the client created in the constructor with a custom client.
     *
     * This is primarily useful for testing purposes.
     */
    public function setClient(Client $client): void
    {
        $this->client = $client;
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

    /**
     * @param string $site_id The domain name of the site in Plausible.
     * @param string $event_name The name of the event to record. To record a
     *     page view in Plausible, use the value "pageview".
     * @param string $url The full URL (including protocol, e.g., "https://")
     *     where the event was triggered.
     * @param string $user_agent The raw value of the User-Agent header
     *     included in the request for this event.
     * @param string $ip_address The IP address for the client that triggered
     *     this event. Be careful to use the correct IP address of the client
     *     where the request originated, which might be provided in the
     *     X-Forwarded-For (or other) header.
     * @param string|null $referrer The referrer URL for this event, if available.
     * @param array<string, scalar|null> | null $properties Optional custom
     *     properties for the event.
     * @param array{currency: string, amount: float|string} | null $revenue Optional
     *     revenue data for the event. This should include the properties
     *     "currency," which maps to an ISO 4217 currency code, and "amount,"
     *     which can be a float or a string.
     *
     * @return bool
     */
    public function recordEvent(
        string $site_id,
        string $event_name,
        string $url,
        string $user_agent,
        string $ip_address,
        ?string $referrer = null,
        ?array $properties = null,
        ?array $revenue = null
    ): bool {
        $response = $this->client->post('event', [
            'headers' => [
                'user-agent' => $user_agent,
                'x-forwarded-for' => $ip_address,
                'content-type' => 'application/json',
            ],
            'body' => json_encode(array_filter([
                'domain' => $site_id,
                'name' => $event_name,
                'url' => $url,
                'referrer' => $referrer,
                'props' => $properties,
                'revenue' => $revenue,
            ])),
        ]);

        return $response->getStatusCode() === 202;
    }
}
