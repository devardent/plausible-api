<?php

namespace Devarts\PlausiblePHP\Response;

use DateTime;
use Exception;

/**
 * @property DateTime $date
 * @property int|null $bounce_rate
 * @property int|null $visit_duration
 * @property int|null $pageviews
 * @property int|null $visits
 * @property int|null $visitors
 * @property float|null $views_per_visit
 * @property int|null $events
 * @property float|null $conversion_rate
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