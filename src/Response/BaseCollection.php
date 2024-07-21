<?php

namespace Devarts\PlausiblePHP\Response;

use Countable;
use Devarts\PlausiblePHP\Contract\ApiResponseObject;
use Iterator;

abstract class BaseCollection implements Iterator, Countable, ApiResponseObject
{
    protected int $position = 0;
    protected array $items = [];

    protected function __construct() {}

    public function rewind(): void 
    {
        $this->position = 0;
    }

    public function key(): int 
    {
        return $this->position;
    }

    public function next(): void 
    {
        ++$this->position;
    }

    public function valid(): bool 
    {
        return isset($this->items[$this->position]);
    }

    public function count(): int
    {
        return count($this->items);
    }
}