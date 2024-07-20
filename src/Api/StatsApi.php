<?php

namespace Devarts\PlausiblePHP\Api;

use Devarts\PlausiblePHP\Response\AggregatedMetrics;
use Devarts\PlausiblePHP\Response\BreakdownCollection;
use Devarts\PlausiblePHP\Response\TimeseriesCollection;

class StatsApi extends BaseApi
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
        $response = $this->configuration->getClient()->get('v1/stats/timeseries', [
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
        $response = $this->configuration->getClient()->get('v1/stats/breakdown', [
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
}