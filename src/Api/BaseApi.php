<?php

namespace Devarts\PlausiblePHP\Api;

use Devarts\PlausiblePHP\Contract\ConfigurationInterface;

abstract class BaseApi
{
    protected ConfigurationInterface $configuration;

    public function __construct(ConfigurationInterface $configuration)
    {
        $this->configuration = $configuration;
    }
}