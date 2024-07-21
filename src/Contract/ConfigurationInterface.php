<?php

namespace Devarts\PlausiblePHP\Contract;

use GuzzleHttp\ClientInterface;

interface ConfigurationInterface
{
    public function getClient(): ClientInterface;
}