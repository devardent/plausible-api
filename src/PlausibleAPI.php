<?php

namespace Devarts\PlausiblePHP;

use Devarts\PlausiblePHP\Api\EventsAPI;
use Devarts\PlausiblePHP\Api\SitesAPI;
use Devarts\PlausiblePHP\Api\StatsAPI;

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

    public function stats(): StatsAPI
    {
        return new StatsAPI($this->configuration);
    }

    public function events(): EventsAPI
    {
        return new EventsAPI($this->configuration);
    }

    public function sites(): SitesAPI
    {
        return new SitesAPI($this->configuration);
    }
}
