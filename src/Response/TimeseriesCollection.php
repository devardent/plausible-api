<?php

namespace Devarts\PlausiblePHP\Response;

class TimeseriesCollection extends BaseCollection
{
    public static function fromApiResponse(string $json): self
    {
        $data = json_decode($json, true)['results'];

        $timeseries = new self();

        foreach ($data as $item) {
            $timeseries->items[] = TimeseriesItem::fromArray($item);
        }

        return $timeseries;
    }

    public function current(): TimeseriesItem
    {
        return $this->items[$this->position];
    }
}