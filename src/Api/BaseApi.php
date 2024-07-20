<?php

namespace Devarts\PlausiblePHP\Api;

use Devarts\PlausiblePHP\Configuration;

abstract class BaseApi
{
    protected Configuration $configuration;

    public function __construct(Configuration $configuration)
    {
        $this->configuration = $configuration;
    }
}