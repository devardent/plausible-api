<?php

namespace Devarts\PlausiblePHP\Contract;

interface EventsApiInterface
{
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
    ): bool;
}