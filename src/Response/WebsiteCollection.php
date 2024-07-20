<?php

namespace Devarts\PlausiblePHP\Response;

class WebsiteCollection extends BaseCollection
{
    public static function fromArray(array $data): self
    {
        $websites = new self();

        foreach ($data as $item) {
            $websites->items[] = Website::fromArray($item);
        }

        return $websites;
    }

    public function current(): Website
    {
        return $this->items[$this->position];
    }
}