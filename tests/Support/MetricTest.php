<?php

namespace Devarts\PlausiblePHP\Test\Support;

use PHPUnit\Framework\TestCase;
use Devarts\PlausiblePHP\Support\Metric;

class MetricTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_stringify_metrics(): void
    {
        $metric = Metric::create()
            ->add(Metric::VISITS)
            ->add(Metric::VISIT_DURATION);

        $this->assertEquals(
            'visits,visit_duration',
            $metric->toString()
        );
    }

    /**
     * @test
     */
    public function it_should_stringify_empty_metrics(): void
    {
        $metric = Metric::create();

        $this->assertEquals('', $metric->toString());
    }

    /**
     * @test
     */
    public function it_should_add_custom_metric(): void
    {
        $metric = Metric::create()->add('custom_metric');

        $this->assertEquals(
            'custom_metric',
            $metric->toString()
        );
    }
}