<?php

namespace Devarts\PlausiblePHP\Contract;

interface PlausibleApiInterface
{
    public function stats(): StatsApiInterface;

    public function events(): EventsApiInterface;

    public function sites(): SitesApiInterface;
}