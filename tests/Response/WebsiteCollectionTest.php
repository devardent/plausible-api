<?php

namespace Devarts\PlausiblePHP\Test\Response;

use Devarts\PlausiblePHP\Response\GoalCollection;
use Devarts\PlausiblePHP\Response\WebsiteCollection;
use PHPUnit\Framework\TestCase;

class WebsiteCollectionTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_create_website_collection_from_array(): void
    {
        $websites = WebsiteCollection::fromArray([
            [
                "domain"   => "test-domain1.com",
                "timezone" => "Europe/London",
            ],
            [
                "domain"   => "test-domain2.com",
                "timezone" => "Europe/London",
            ],
        ]);

        $item_1 = $websites->current();

        $this->assertEquals('test-domain1.com', $item_1->domain);
        $this->assertEquals('Europe/London', $item_1->timezone);

        $websites->next();

        $item_2 = $websites->current();

        $this->assertEquals('test-domain2.com', $item_2->domain);
        $this->assertEquals('Europe/London', $item_2->timezone);
    }
}