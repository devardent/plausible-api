<?php

namespace Devarts\PlausiblePHP\Contract;

use Devarts\PlausiblePHP\Response\AggregatedMetrics;
use Devarts\PlausiblePHP\Response\BreakdownCollection;
use Devarts\PlausiblePHP\Response\TimeseriesCollection;

interface StatsApiInterface
{
    public function getRealtimeVisitors(string $site_id): int;

    public function getAggregate(string $site_id, array $extras = []): AggregatedMetrics;

    public function getTimeseries(string $site_id, array $extras = []): TimeseriesCollection;

    public function getBreakdown(string $site_id, string $property, array $extras = []): BreakdownCollection;
}