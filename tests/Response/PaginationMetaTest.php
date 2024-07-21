<?php

namespace Devarts\PlausiblePHP\Test\Response;

use Devarts\PlausiblePHP\Response\PaginationMeta;
use PHPUnit\Framework\TestCase;

class PaginationMetaTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_create_pagination_meta_from_array(): void
    {
        $meta = PaginationMeta::fromArray([
            'after'  => 'some_id',
            'before' => null,
            'limit'  => 100,
        ]);

        $this->assertEquals('some_id', $meta->after);
        $this->assertNull($meta->before);
        $this->assertEquals(100, $meta->limit);
    }
}