<?php

namespace Devarts\PlausiblePHP;

use Devarts\PlausiblePHP\Api\EventsApi;
use Devarts\PlausiblePHP\Api\SitesApi;
use Devarts\PlausiblePHP\Api\StatsApi;

class PlausibleAPI
{
    protected Configuration $configuration;

    public function __construct(Configuration $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * This is primarily useful for testing purposes.
     */
    public function getConfiguration(): Configuration
    {
        return $this->configuration;
    }

    public function stats(): StatsApi
    {
        return new StatsApi($this->configuration);
    }

    public function events(): EventsApi
    {
        return new EventsApi($this->configuration);
    }

    public function sites(): SitesApi
    {
        return new SitesApi($this->configuration);
    }
}
