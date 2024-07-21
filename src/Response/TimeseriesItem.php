<?php

namespace Devarts\PlausiblePHP\Response;

use DateTime;

/**
 * @property DateTime $date
 * @property int|float|null $bounce_rate
 * @property int|float|null $visit_duration
 * @property int|float|null $pageviews
 * @property int|float|null $visits
 * @property int|float|null $visitors
 * @property int|float|null $views_per_visit
 * @property int|float|null $events
 * @property int|float|null $conversion_rate
 */
class TimeseriesItem extends BaseObject
{
    public static function fromArray(array $data): self
    {
        $timeseries_item = new self();

        $timeseries_item->createProperties($data);

        return $timeseries_item;
    }

    protected function createProperty($name, $value): void
    {
        switch ($name) {
            case 'date':
                $this->$name = new DateTime($value);
                break;
            default:
                parent::createProperty($name, $value);
        }
    }
}