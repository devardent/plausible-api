<?php

namespace Devarts\PlausiblePHP\Response;

/**
 * @property string $name
 * @property string $page
 * @property string $entry_page
 * @property string $exit_page
 * @property string $source
 * @property string $referrer
 * @property string $utm_medium
 * @property string $utm_source
 * @property string $utm_campaign
 * @property string $utm_content
 * @property string $utm_term
 * @property string $device
 * @property string $browser
 * @property string $browser_version
 * @property string $os
 * @property string $os_version
 * @property string $country
 * @property string $region
 * @property string $city
 * @property int|null $visitors
 * @property int|null $pageviews
 * @property int|null $bounce_rate
 * @property int|null $visit_duration
 * @property int|null $events
 * @property int|null $visits
 */
class BreakdownItem extends BaseObject
{
    public static function fromArray(array $data): self
    {
        $breakdown_item = new self();

        $breakdown_item->createProperties($data);

        return $breakdown_item;
    }
}