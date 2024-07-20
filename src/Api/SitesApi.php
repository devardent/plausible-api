<?php

namespace Devarts\PlausiblePHP\Api;

use Devarts\PlausiblePHP\Response\Goal;
use Devarts\PlausiblePHP\Response\SharedLink;
use Devarts\PlausiblePHP\Response\Website;

class SitesApi extends BaseApi
{
    public function createWebsite(array $payload): Website
    {
        $response = $this->client->post('v1/sites', [
            'form_params' => $payload,
        ]);

        return Website::fromApiResponse($response->getBody()->getContents());
    }

    public function updateWebsite(string $site_id, array $payload): Website
    {
        $response = $this->client->put('v1/sites/' . urlencode($site_id), [
            'form_params' => $payload,
        ]);

        return Website::fromApiResponse($response->getBody()->getContents());
    }

    public function deleteWebsite(string $site_id): bool
    {
        $response = $this->client->delete('v1/sites/' . urlencode($site_id));

        return json_decode($response->getBody()->getContents(), true)['deleted'];
    }

    public function getWebsite(string $site_id): Website
    {
        $response = $this->client->get('v1/sites/' . urlencode($site_id));

        return Website::fromApiResponse($response->getBody()->getContents());
    }

    public function createSharedLink(array $payload): SharedLink
    {
        $response = $this->client->put('v1/sites/shared-links', [
            'form_params' => $payload,
        ]);

        return SharedLink::fromApiResponse($response->getBody()->getContents());
    }

    public function createGoal(array $payload): Goal
    {
        $response = $this->client->put('v1/sites/goals', [
            'form_params' => $payload,
        ]);

        return Goal::fromApiResponse($response->getBody()->getContents());
    }

    public function deleteGoal(int $goal_id, string $site_id): bool
    {
        $response = $this->client->delete('v1/sites/goals/' . urlencode((string) $goal_id), [
            'form_params' => [
                'site_id' => $site_id,
            ],
        ]);

        return json_decode($response->getBody()->getContents(), true)['deleted'];
    }
}