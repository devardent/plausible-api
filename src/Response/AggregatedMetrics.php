<?php

namespace Devarts\PlausiblePHP\Response;

/**
 * @property AggregatedMetric $visitors
 * @property AggregatedMetric $pageviews
 * @property AggregatedMetric $bounce_rate
 * @property AggregatedMetric $visit_duration
 * @property AggregatedMetric $events
 * @property AggregatedMetric $visits
 */
class AggregatedMetrics extends BaseObject
{
    public static function fromApiResponse(string $json): self
    {
        $data = json_decode($json, true)['results'];

        $aggregated_metrics = new self();

        $aggregated_metrics->createProperties($data);

        return $aggregated_metrics;
    }

    protected function createProperty($name, $value): void
    {
        $this->$name = AggregatedMetric::fromArray($value);
    }
}