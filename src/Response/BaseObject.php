<?php

namespace Devarts\PlausiblePHP\Response;

use Devarts\PlausiblePHP\Contract\ApiResponseObject;
use stdClass;

abstract class BaseObject extends stdClass implements ApiResponseObject
{
    protected function __construct() {}

    protected function createProperties(array $data): void
    {
        foreach ($data as $name => $value) {
            $this->createProperty($name, $value);
        }
    }

    protected function createProperty($name, $value): void
    {
        $this->$name = $value;
    }
}