<?php

namespace Devarts\PlausiblePHP\Api;

use GuzzleHttp\Client;

abstract class BaseApi
{
    protected Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }
}