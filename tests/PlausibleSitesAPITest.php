<?php

namespace Devarts\PlausiblePHP\Test;

use Devarts\PlausiblePHP\PlausibleAPI;

class PlausibleSitesAPITest extends PlausibleAPITestCase
{
    /**
     * @test
     */
    public function it_should_create_website(): void
    {
        $expected_response =
        <<<JSON
        {
            "domain": "test-domain.com",
            "timezone": "Europe/London"
        }
        JSON;

        $configuration = $this->mockConfiguration();

        $configuration
            ->getClient()
            ->expects($this->once())
            ->method('post')
            ->with(
                $this->equalTo('v1/sites'),
                $this->equalTo([
                    'form_params' => [
                        'domain' => 'test-domain.com',
                    ],
                ])
            )->willReturn($this->mockResponse($expected_response));

        $plausible = new PlausibleAPI($configuration);

        $website = $plausible->sites()->createWebsite(['domain' => 'test-domain.com']);

        $this->assertEquals('test-domain.com', $website->domain);
        $this->assertEquals('Europe/London', $website->timezone);
    }

    /**
     * @test
     */
    public function it_should_update_website(): void
    {
        $expected_response =
        <<<JSON
        {
            "domain": "test-domain-edit.com",
            "timezone": "Europe/London"
        }
        JSON;

        $configuration = $this->mockConfiguration();

        $configuration
            ->getClient()
            ->expects($this->once())
            ->method('put')
            ->with(
                $this->equalTo('v1/sites/' . urlencode('test-domain.com')),
                $this->equalTo([
                    'form_params' => [
                        'domain' => 'test-domain-edit.com',
                    ],
                ])
            )->willReturn($this->mockResponse($expected_response));

        $plausible = new PlausibleAPI($configuration);

        $website = $plausible->sites()->updateWebsite('test-domain.com', [
            'domain' => 'test-domain-edit.com',
        ]);

        $this->assertEquals('test-domain-edit.com', $website->domain);
        $this->assertEquals('Europe/London', $website->timezone);
    }

    /**
     * @test
     */
    public function it_should_delete_website(): void
    {
        $expected_response =
        <<<JSON
        {
            "deleted": "true"
        }
        JSON;

        $configuration = $this->mockConfiguration();

        $configuration
            ->getClient()
            ->expects($this->once())
            ->method('delete')
            ->with(
                $this->equalTo('v1/sites/' . urlencode('test-domain.com'))
            )->willReturn($this->mockResponse($expected_response));

        $plausible = new PlausibleAPI($configuration);

        $this->assertTrue($plausible->sites()->deleteWebsite('test-domain.com'));
    }

    /**
     * @test
     */
    public function it_should_list_websites(): void
    {
        $expected_response =
        <<<JSON
        {
            "sites": [
                {
                    "domain": "test-domain1.com",
                    "timezone": "Europe/London"
                },
                {
                    "domain": "test-domain2.com",
                    "timezone": "Europe/London"
                }
            ],
            "meta": {
                "after": "id_1",
                "before": "id_5",
                "limit": 100
            }
        }
        JSON;

        $configuration = $this->mockConfiguration();

        $configuration
            ->getClient()
            ->expects($this->once())
            ->method('get')
            ->with(
                $this->equalTo('v1/sites'),
                $this->equalTo([
                    'query' => [
                        'after'  => 'id_1',
                        'before' => 'id_5',
                        'limit'  => 100,
                    ],
                ])
            )->willReturn($this->mockResponse($expected_response));

        $plausible = new PlausibleAPI($configuration);

        $websites_list_response = $plausible->sites()->listWebsites([
            'after'  => 'id_1',
            'before' => 'id_5',
            'limit'  => 100,
        ]);

        $this->assertEquals('id_1', $websites_list_response->meta->after);
        $this->assertEquals('id_5', $websites_list_response->meta->before);
        $this->assertEquals(100, $websites_list_response->meta->limit);

        $this->assertCount(2, $websites_list_response->sites);

        $site_1 = $websites_list_response->sites->current();

        $this->assertEquals('test-domain1.com', $site_1->domain);
        $this->assertEquals('Europe/London', $site_1->timezone);

        $websites_list_response->sites->next();

        $site_2 = $websites_list_response->sites->current();

        $this->assertEquals('test-domain2.com', $site_2->domain);
        $this->assertEquals('Europe/London', $site_2->timezone);
    }

    /**
     * @test
     */
    public function it_should_get_website(): void
    {
        $expected_response =
        <<<JSON
        {
            "domain": "test-domain.com",
            "timezone": "Europe/London"
        }
        JSON;

        $configuration = $this->mockConfiguration();

        $configuration
            ->getClient()
            ->expects($this->once())
            ->method('get')
            ->with(
                $this->equalTo('v1/sites/' . urlencode('test-domain.com'))
            )->willReturn($this->mockResponse($expected_response));

        $plausible = new PlausibleAPI($configuration);

        $website = $plausible->sites()->getWebsite('test-domain.com');

        $this->assertEquals('test-domain.com', $website->domain);
        $this->assertEquals('Europe/London', $website->timezone);
    }

    /**
     * @test
     */
    public function it_should_create_shared_link(): void
    {
        $expected_response =
        <<<JSON
        {
            "name": "My Shared Link",
            "url": "https://plausible.io/share/site.com?auth=<random>"
        }
        JSON;

        $configuration = $this->mockConfiguration();

        $configuration
            ->getClient()
            ->expects($this->once())
            ->method('put')
            ->with(
                $this->equalTo('v1/sites/shared-links'),
                $this->equalTo([
                    'form_params' => [
                        'site_id' => 'test-domain.com',
                        'name'    => 'My Shared Link'
                    ],
                ])
            )->willReturn($this->mockResponse($expected_response));

        $plausible = new PlausibleAPI($configuration);

        $link = $plausible->sites()->createSharedLink([
            'site_id' => 'test-domain.com',
            'name'    => 'My Shared Link'
        ]);

        $this->assertEquals('My Shared Link', $link->name);
        $this->assertEquals('https://plausible.io/share/site.com?auth=<random>', $link->url);
    }

    /**
     * @test
     */
    public function it_should_list_goals(): void
    {
        $expected_response =
        <<<JSON
        {
            "goals": [
                {
                    "id": "1",
                    "goal_type": "event",
                    "display_name": "Signup",
                    "event_name": "Signup",
                    "page_path": null
                },
                {
                    "id": "2",
                    "goal_type": "page",
                    "display_name": "Visit /register",
                    "event_name": null,
                    "page_path": "/register"
                }
            ],
            "meta": {
                "after": "id_1",
                "before": "id_5",
                "limit": 100
            }
        }
        JSON;

        $configuration = $this->mockConfiguration();

        $configuration
            ->getClient()
            ->expects($this->once())
            ->method('get')
            ->with(
                $this->equalTo('v1/sites/goals'),
                $this->equalTo([
                    'query' => [
                        'site_id' => 'test-domain.com',
                        'after'   => 'id_1',
                        'before'  => 'id_5',
                        'limit'   => 100,
                    ],
                ])
            )->willReturn($this->mockResponse($expected_response));

        $plausible = new PlausibleAPI($configuration);

        $goals_list_response = $plausible->sites()->listGoals('test-domain.com', [
            'after'  => 'id_1',
            'before' => 'id_5',
            'limit'  => 100,
        ]);

        $this->assertEquals('id_1', $goals_list_response->meta->after);
        $this->assertEquals('id_5', $goals_list_response->meta->before);
        $this->assertEquals(100, $goals_list_response->meta->limit);

        $this->assertCount(2, $goals_list_response->goals);

        $goal_1 = $goals_list_response->goals->current();

        $this->assertEquals(1, $goal_1->id);
        $this->assertEquals('event', $goal_1->goal_type);
        $this->assertEquals('Signup', $goal_1->display_name);
        $this->assertEquals('Signup', $goal_1->event_name);
        $this->assertNull($goal_1->page_path);

        $goals_list_response->goals->next();

        $goal_2 = $goals_list_response->goals->current();

        $this->assertEquals(2, $goal_2->id);
        $this->assertEquals('page', $goal_2->goal_type);
        $this->assertEquals('Visit /register', $goal_2->display_name);
        $this->assertNull($goal_2->event_name);
        $this->assertEquals('/register', $goal_2->page_path);
    }

    /**
     * @test
     */
    public function it_should_create_goal(): void
    {
        $expected_response =
        <<<JSON
        {
            "domain": "test-domain.com",
            "id": "1",
            "display_name": "Signup",
            "goal_type": "event",
            "event_name": "Signup",
            "page_path": null
        }
        JSON;

        $configuration = $this->mockConfiguration();

        $configuration
            ->getClient()
            ->expects($this->once())
            ->method('put')
            ->with(
                $this->equalTo('v1/sites/goals'),
                $this->equalTo([
                    'form_params' => [
                        'site_id'    => 'test_domain.com',
                        'goal_type'  => 'event',
                        'event_name' => 'Signup',
                    ],
                ])
            )->willReturn($this->mockResponse($expected_response));

        $plausible = new PlausibleAPI($configuration);

        $goal = $plausible->sites()->createGoal([
            'site_id'    => 'test_domain.com',
            'goal_type'  => 'event',
            'event_name' => 'Signup',
        ]);

        $this->assertEquals(1, $goal->id);
        $this->assertEquals('event', $goal->goal_type);
        $this->assertEquals('Signup', $goal->display_name);
        $this->assertEquals('Signup', $goal->event_name);
        $this->assertNull($goal->page_path);
    }

    /**
     * @test
     */
    public function it_should_delete_goal(): void
    {
        $expected_response =
        <<<JSON
        {
            "deleted": "true"
        }
        JSON;

        $configuration = $this->mockConfiguration();

        $configuration
            ->getClient()
            ->expects($this->once())
            ->method('delete')
            ->with(
                $this->equalTo('v1/sites/goals/1'),
                $this->equalTo([
                    'form_params' => [
                        'site_id'    => 'test-domain.com',
                    ],
                ])
            )->willReturn($this->mockResponse($expected_response));

        $plausible = new PlausibleAPI($configuration);

        $this->assertTrue($plausible->sites()->deleteGoal(1, 'test-domain.com'));
    }
}