<?php

namespace Devarts\PlausiblePHP\Contract;

use Devarts\PlausiblePHP\Response\Goal;
use Devarts\PlausiblePHP\Response\GoalsListResponse;
use Devarts\PlausiblePHP\Response\SharedLink;
use Devarts\PlausiblePHP\Response\Website;
use Devarts\PlausiblePHP\Response\WebsitesListResponse;

interface SitesApiInterface
{
    public function createWebsite(array $payload): Website;

    public function updateWebsite(string $site_id, array $payload): Website;

    public function deleteWebsite(string $site_id): bool;

    public function listWebsites(array $params): WebsitesListResponse;

    public function getWebsite(string $site_id): Website;

    public function createSharedLink(array $payload): SharedLink;

    public function listGoals(string $site_id, array $extras = []): GoalsListResponse;

    public function createGoal(array $payload): Goal;

    public function deleteGoal(int $goal_id, string $site_id): bool;
}