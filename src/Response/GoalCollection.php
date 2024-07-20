<?php

namespace Devarts\PlausiblePHP\Response;

class GoalCollection extends BaseCollection
{
    public static function fromApiResponse(string $json): self
    {
        $data = json_decode($json, true)['goals'];

        return self::fromArray($data);
    }

    public static function fromArray(array $data): self
    {
        $goals = new self();

        foreach ($data as $item) {
            $goals->items[] = Goal::fromArray($item);
        }

        return $goals;
    }

    public function current(): Website
    {
        return $this->items[$this->position];
    }
}