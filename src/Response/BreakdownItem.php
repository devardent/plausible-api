<?php

namespace Devarts\PlausiblePHP\Response;

/**
 * @property string $goal
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
 * @property int|null $bounce_rate
 * @property int|null $visit_duration
 * @property int|null $pageviews
 * @property int|null $visits
 * @property int|null $visitors
 * @property float|null $views_per_visit
 * @property int|null $events
 * @property float|null $conversion_rate
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