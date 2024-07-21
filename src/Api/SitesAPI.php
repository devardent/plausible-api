<?php

namespace Devarts\PlausiblePHP\Api;

use Devarts\PlausiblePHP\Contract\SitesApiInterface;
use Devarts\PlausiblePHP\Response\Goal;
use Devarts\PlausiblePHP\Response\GoalsListResponse;
use Devarts\PlausiblePHP\Response\SharedLink;
use Devarts\PlausiblePHP\Response\Website;
use Devarts\PlausiblePHP\Response\WebsitesListResponse;

class SitesAPI extends BaseAPI implements SitesApiInterface
{
    public function createWebsite(array $payload): Website
    {
        $response = $this->configuration->getClient()->post('v1/sites', [
            'form_params' => $payload,
        ]);

        return Website::fromApiResponse($response->getBody()->getContents());
    }

    public function updateWebsite(string $site_id, array $payload): Website
    {
        $response = $this->configuration->getClient()->put('v1/sites/' . urlencode($site_id), [
            'form_params' => $payload,
        ]);

        return Website::fromApiResponse($response->getBody()->getContents());
    }

    public function deleteWebsite(string $site_id): bool
    {
        $response = $this->configuration->getClient()->delete('v1/sites/' . urlencode($site_id));

        return json_decode($response->getBody()->getContents(), true)['deleted'];
    }

    public function listWebsites(array $params): WebsitesListResponse
    {
        $response = $this->configuration->getClient()->get('v1/sites', [
            'query' => $params,
        ]);

        return WebsitesListResponse::fromApiResponse($response->getBody()->getContents());
    }

    public function getWebsite(string $site_id): Website
    {
        $response = $this->configuration->getClient()->get('v1/sites/' . urlencode($site_id));

        return Website::fromApiResponse($response->getBody()->getContents());
    }

    public function createSharedLink(array $payload): SharedLink
    {
        $response = $this->configuration->getClient()->put('v1/sites/shared-links', [
            'form_params' => $payload,
        ]);

        return SharedLink::fromApiResponse($response->getBody()->getContents());
    }

    public function listGoals(string $site_id, array $extras = []): GoalsListResponse
    {
        $response = $this->configuration->getClient()->get('v1/sites/goals', [
            'query' => array_merge(
                $extras,
                [
                    'site_id' => $site_id,
                ],
            ),
        ]);

        return GoalsListResponse::fromApiResponse($response->getBody()->getContents());
    }

    public function createGoal(array $payload): Goal
    {
        $response = $this->configuration->getClient()->put('v1/sites/goals', [
            'form_params' => $payload,
        ]);

        return Goal::fromApiResponse($response->getBody()->getContents());
    }

    public function deleteGoal(int $goal_id, string $site_id): bool
    {
        $response = $this->configuration->getClient()->delete('v1/sites/goals/' . urlencode((string) $goal_id), [
            'form_params' => [
                'site_id' => $site_id,
            ],
        ]);

        return json_decode($response->getBody()->getContents(), true)['deleted'];
    }
}