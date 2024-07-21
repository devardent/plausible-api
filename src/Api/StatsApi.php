<?php

namespace Devarts\PlausiblePHP\Api;

use Devarts\PlausiblePHP\Contract\StatsApiInterface;
use Devarts\PlausiblePHP\Response\AggregatedMetrics;
use Devarts\PlausiblePHP\Response\BreakdownCollection;
use Devarts\PlausiblePHP\Response\TimeseriesCollection;
use Devarts\PlausiblePHP\Support\Filter;
use Devarts\PlausiblePHP\Support\Metric;

class StatsApi extends BaseApi implements StatsApiInterface
{
    public function getRealtimeVisitors(string $site_id): int
    {
        $response = $this->configuration->getClient()->get('v1/stats/realtime/visitors', [
            'query' => [
                'site_id' => $site_id,
            ],
        ]);

        return (int) $response->getBody()->getContents();
    }

    public function getAggregate(string $site_id, array $extras = []): AggregatedMetrics
    {
        $response = $this->configuration->getClient()->get('v1/stats/aggregate', [
            'query' => array_merge(
                $this->normalizeParams($extras),
                [
                    'site_id' => $site_id,
                ]
            ),
        ]);

        return AggregatedMetrics::fromApiResponse($response->getBody()->getContents());
    }

    public function getTimeseries(string $site_id, array $extras = []): TimeseriesCollection
    {
        $response = $this->configuration->getClient()->get('v1/stats/timeseries', [
            'query' => array_merge(
                $this->normalizeParams($extras),
                [
                    'site_id' => $site_id,
                ]
            ),
        ]);

        return TimeseriesCollection::fromApiResponse($response->getBody()->getContents());
    }

    public function getBreakdown(string $site_id, string $property, array $extras = []): BreakdownCollection
    {
        $response = $this->configuration->getClient()->get('v1/stats/breakdown', [
            'query' => array_merge(
                $this->normalizeParams($extras),
                [
                    'site_id' => $site_id,
                    'property' => $property,
                ]
            ),
        ]);

        return BreakdownCollection::fromApiResponse($response->getBody()->getContents());
    }

    private function normalizeParams(array $params): array
    {
        return array_map(function ($value) {
            if ($value instanceof Metric) {
                return $value->toString();
            }
            if ($value instanceof Filter) {
                return $value->toString();
            }
            return $value;
        }, $params);
    }
}