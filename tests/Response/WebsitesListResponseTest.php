<?php

namespace Devarts\PlausiblePHP\Test\Response;

use Devarts\PlausiblePHP\Response\WebsitesListResponse;
use PHPUnit\Framework\TestCase;

class WebsitesListResponseTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_create_website_list_response_from_api_response(): void
    {
        $response = WebsitesListResponse::fromApiResponse(
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

        $websites = $response->sites;

        $item_1 = $websites->current();

        $this->assertEquals('test-domain1.com', $item_1->domain);
        $this->assertEquals('Europe/London', $item_1->timezone);

        $websites->next();

        $item_2 = $websites->current();

        $this->assertEquals('test-domain2.com', $item_2->domain);
        $this->assertEquals('Europe/London', $item_2->timezone);
    }
}