<?php

namespace Devarts\PlausiblePHP\Response;

/**
 * @property float $value
 * @property float $change
 */
class AggregatedMetric extends BaseObject
{
    public static function fromArray(array $data): self
    {
        $aggregated_metric = new self();

        $aggregated_metric->createProperties($data);

        return $aggregated_metric;
    }
}