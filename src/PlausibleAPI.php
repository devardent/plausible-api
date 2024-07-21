<?php

namespace Devarts\PlausiblePHP;

use Devarts\PlausiblePHP\Api\EventsAPI;
use Devarts\PlausiblePHP\Api\SitesAPI;
use Devarts\PlausiblePHP\Api\StatsAPI;
use Devarts\PlausiblePHP\Contract\EventsApiInterface;
use Devarts\PlausiblePHP\Contract\PlausibleApiInterface;
use Devarts\PlausiblePHP\Contract\SitesApiInterface;
use Devarts\PlausiblePHP\Contract\StatsApiInterface;

class PlausibleAPI implements PlausibleApiInterface
{
    private StatsApiInterface $stats_api;
    private EventsApiInterface $events_api;
    private SitesApiInterface $sites_api;

    public function __construct(Configuration $configuration)
    {
        $this->stats_api = new StatsAPI($configuration);
        $this->events_api = new EventsAPI($configuration);
        $this->sites_api = new SitesAPI($configuration);
    }

    public function stats(): StatsApiInterface
    {
        return $this->stats_api;
    }

    public function events(): EventsApiInterface
    {
        return $this->events_api;
    }

    public function sites(): SitesApiInterface
    {
        return $this->sites_api;
    }
}
