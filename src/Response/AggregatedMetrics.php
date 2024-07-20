<?php

namespace Devarts\PlausiblePHP\Response;

/**
 * @property AggregatedMetric $visitors
 * @property AggregatedMetric $pageviews
 * @property AggregatedMetric $bounce_rate
 * @property AggregatedMetric $visit_duration
 * @property AggregatedMetric $events
 * @property AggregatedMetric $visits
 * @property AggregatedMetric $conversion_rate
 * @property AggregatedMetric $time_on_page
 */
class AggregatedMetrics extends BaseObject
{
    public static function fromApiResponse(string $json): self
    {
        $data = json_decode($json, true)['results'];

        return self::fromArray($data);
    }

    public static function fromArray(array $data): self
    {
        $aggregated_metrics = new self();

        $aggregated_metrics->createProperties($data);

        return $aggregated_metrics;
    }

    protected function createProperty($name, $value): void
    {
        $this->$name = AggregatedMetric::fromArray($value);
    }
}