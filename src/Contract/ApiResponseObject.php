<?php

namespace Devarts\PlausiblePHP\Contract;

interface ApiResponseObject
{
    public static function fromArray(array $data): self;
}