<?php

namespace Devarts\PlausiblePHP\Response;

class GoalCollection extends BaseCollection
{
    public static function fromArray(array $data): self
    {
        $goals = new self();

        foreach ($data as $item) {
            $goals->items[] = Goal::fromArray($item);
        }

        return $goals;
    }

    public function current(): Goal
    {
        return $this->items[$this->position];
    }
}