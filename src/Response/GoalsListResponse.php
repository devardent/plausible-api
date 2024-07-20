<?php

namespace Devarts\PlausiblePHP\Response;

/**
 * @property GoalCollection $goals
 * @property PaginationMeta $meta
 */
class GoalsListResponse extends BaseObject
{
    public static function fromApiResponse(string $json): self
    {
        $data = json_decode($json, true);

        $goals_list_response = new self();

        $goals_list_response->createProperties($data);

        return $goals_list_response;
    }

    protected function createProperty($name, $value): void
    {
        switch ($name) {
            case 'goals':
                $this->$name = GoalCollection::fromArray($value);
                break;
            case 'meta':
                $this->$name = PaginationMeta::fromArray($value);
                break;
            default:
                parent::createProperty($name, $value);
        }
    }
}