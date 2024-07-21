<?php

namespace Devarts\PlausiblePHP\Api;

use Devarts\PlausiblePHP\Contract\EventsApiInterface;

class EventsApi extends BaseApi implements EventsApiInterface
{
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
        $response = $this->configuration->getClient()->post('event', [
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