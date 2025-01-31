<?php

namespace Devarts\PlausiblePHP\Response;

/**
 * @property WebsiteCollection $sites
 * @property PaginationMeta $meta
 */
class WebsitesListResponse extends BaseObject
{
    public static function fromApiResponse(string $json): self
    {
        $data = json_decode($json, true);

        return self::fromArray($data);
    }

    public static function fromArray(array $data): self
    {
        $websites_list_response = new self();

        $websites_list_response->createProperties($data);

        return $websites_list_response;
    }

    protected function createProperty($name, $value): void
    {
        switch ($name) {
            case 'sites':
                $this->$name = WebsiteCollection::fromArray($value);
                break;
            case 'meta':
                $this->$name = PaginationMeta::fromArray($value);
                break;
            default:
                parent::createProperty($name, $value);
        }
    }
}