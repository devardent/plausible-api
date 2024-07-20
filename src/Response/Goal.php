<?php

namespace Devarts\PlausiblePHP\Response;

/**
 * @property int $id
 * @property string $display_name
 * @property string $domain
 * @property string $goal_type
 * @property string|null $event_name
 * @property string|null $page_path
 */
class Goal extends BaseObject
{
    public static function fromApiResponse(string $json): self
    {
        $data = json_decode($json, true);

        return self::fromArray($data);
    }

    public static function fromArray(array $data): self
    {
        $goal = new self();

        $goal->createProperties($data);

        return $goal;
    }
}