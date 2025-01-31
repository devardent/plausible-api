<?php

namespace Devarts\PlausiblePHP\Response;

/**
 * @property string $domain
 * @property string $timezone
 */
class Website extends BaseObject
{
    public static function fromApiResponse(string $json): self
    {
        $data = json_decode($json, true);

        return self::fromArray($data);
    }

    public static function fromArray(array $data): self
    {
        $website = new self();

        $website->createProperties($data);

        return $website;
    }
}