<?php

namespace Devarts\PlausiblePHP\Test\Response;

use Devarts\PlausiblePHP\Response\GoalsListResponse;
use PHPUnit\Framework\TestCase;

class GoalsListResponseTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_create_goals_list_response_from_api_response(): void
    {
        $response = GoalsListResponse::fromApiResponse(
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
                        "after": "some_id",
                        "before": null,
                        "limit": 100
                    }
                }
            JSON
        );

        $this->assertEquals('some_id', $response->meta->after);
        $this->assertNull($response->meta->before);
        $this->assertEquals(100, $response->meta->limit);

        $goals = $response->goals;

        $item_1 = $goals->current();

        $this->assertEquals(1, $item_1->id);
        $this->assertEquals('event', $item_1->goal_type);
        $this->assertEquals('Signup', $item_1->display_name);
        $this->assertEquals('Signup', $item_1->event_name);
        $this->assertNull($item_1->page_path);

        $goals->next();

        $item_2 = $goals->current();

        $this->assertEquals(2, $item_2->id);
        $this->assertEquals('page', $item_2->goal_type);
        $this->assertEquals('Visit /register', $item_2->display_name);
        $this->assertNull($item_2->event_name);
        $this->assertEquals('/register', $item_2->page_path);
    }
}