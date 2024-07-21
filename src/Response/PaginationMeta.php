<?php

namespace Devarts\PlausiblePHP\Response;

/**
 * @property string|null $after
 * @property string|null $before
 * @property int|null $limit
 */
class PaginationMeta extends BaseObject
{
    public static function fromArray(array $data): self
    {
        $website = new self();

        $website->createProperties($data);

        return $website;
    }
}