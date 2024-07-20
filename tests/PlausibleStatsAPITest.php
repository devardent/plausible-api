<?php

namespace Devarts\PlausiblePHP\Test;

use Devarts\PlausiblePHP\PlausibleAPI;
use Devarts\PlausiblePHP\Support\Metric;
use Devarts\PlausiblePHP\Support\Property;

class PlausibleStatsAPITest extends PlausibleAPITestCase
{
    /**
     * @test
     */
    public function it_should_get_realtime_visitors(): void
    {
        $expected_response = '21';

        $configuration = $this->mockConfiguration();

        $configuration
            ->getClient()
            ->expects($this->once())
            ->method('get')
            ->with(
                $this->equalTo('v1/stats/realtime/visitors'),
                $this->equalTo([
                    'query' => [
                        'site_id' => 'example.com',
                    ],
                ])
            )->willReturn($this->mockResponse($expected_response));

        $plausible = new PlausibleAPI($configuration);

        $this->assertEquals(21, $plausible->stats()->getRealtimeVisitors('example.com'));
    }

    /**
     * @test
     */
    public function it_should_get_aggregate(): void
    {
        $expected_response =
            <<<JSON
            {
                "results": {
                    "bounce_rate": {
                        "value": 53.0
                    },
                    "pageviews": {
                        "value": 673814
                    },
                    "visit_duration": {
                        "value": 86.0
                    },
                    "visitors": {
                        "value": 201524
                    },
                    "events": {
                        "value": 10
                    },
                    "visits": {
                        "value": 506789
                    },
                    "views_per_visit": {
                        "value": 12.5
                    },
                    "conversion_rate": {
                        "value": 13.7
                    },
                    "time_on_page": {
                        "value": 3.2
                    }
                }
            }
            JSON;

        $metric = Metric::create()
            ->addBounceRate()
            ->addPageviews()
            ->addVisitDuration()
            ->addVisitors()
            ->addEvents()
            ->addVisits()
            ->addViewsPerVisit()
            ->addConversionRate()
            ->addTimeOnPage();

        $configuration = $this->mockConfiguration();

        $configuration
            ->getClient()
            ->expects($this->once())
            ->method('get')
            ->with(
                $this->equalTo('v1/stats/aggregate'),
                $this->equalTo([
                    'query' => [
                        'site_id' => 'example.com',
                        'metrics' => $metric->toString(),
                    ],
                ])
            )->willReturn($this->mockResponse($expected_response));

        $plausible = new PlausibleAPI($configuration);

        $aggregate_metrics = $plausible->stats()->getAggregate('example.com', [
            'metrics' => $metric->toString()
        ]);

        $this->assertEquals(53, $aggregate_metrics->bounce_rate->value);
        $this->assertEquals(673814, $aggregate_metrics->pageviews->value);
        $this->assertEquals(86, $aggregate_metrics->visit_duration->value);
        $this->assertEquals(201524, $aggregate_metrics->visitors->value);
        $this->assertEquals(10, $aggregate_metrics->events->value);
        $this->assertEquals(506789, $aggregate_metrics->visits->value);
        $this->assertEquals(12.5, $aggregate_metrics->views_per_visit->value);
        $this->assertEquals(13.7, $aggregate_metrics->conversion_rate->value);
        $this->assertEquals(3.2, $aggregate_metrics->time_on_page->value);
    }

    /**
     * @test
     */
    public function it_should_get_timeseries(): void
    {
        $expected_response =
            <<<JSON
            {
              "results": [
                {
                  "date": "2020-08-01",
                  "visitors": 36085,
                  "bounce_rate": 13.2,
                  "pageviews": 10,
                  "visit_duration": 3.4,
                  "events": 25,
                  "visits": 102320,
                  "views_per_visit": 17.2,
                  "conversion_rate": 32.4
                },
                {
                  "date": "2020-09-01",
                  "visitors": 36085,
                  "bounce_rate": 13.2,
                  "pageviews": 10,
                  "visit_duration": 3.4,
                  "events": 25,
                  "visits": 102320,
                  "views_per_visit": 17.2,
                  "conversion_rate": 32.4
                }
              ]
            }
            JSON;

        $metric = Metric::create()
            ->addBounceRate()
            ->addPageviews()
            ->addVisitDuration()
            ->addVisitors()
            ->addEvents()
            ->addVisits()
            ->addViewsPerVisit()
            ->addConversionRate();

        $configuration = $this->mockConfiguration();

        $configuration
            ->getClient()
            ->expects($this->once())
            ->method('get')
            ->with(
                $this->equalTo('v1/stats/timeseries'),
                $this->equalTo([
                    'query' => [
                        'site_id' => 'example.com',
                        'metrics' => $metric->toString(),
                    ],
                ])
            )->willReturn($this->mockResponse($expected_response));

        $plausible = new PlausibleAPI($configuration);

        $timeseries = $plausible->stats()->getTimeseries('example.com', [
            'metrics' => $metric->toString()
        ]);

        $this->assertCount(2, $timeseries);

        $item_1 = $timeseries->current();

        $this->assertEquals(13.2, $item_1->bounce_rate);
        $this->assertEquals(10, $item_1->pageviews);
        $this->assertEquals(3.4, $item_1->visit_duration);
        $this->assertEquals(36085, $item_1->visitors);
        $this->assertEquals(25, $item_1->events);
        $this->assertEquals(102320, $item_1->visits);
        $this->assertEquals(17.2, $item_1->views_per_visit);
        $this->assertEquals(32.4, $item_1->conversion_rate);
    }

    /**
     * @test
     */
    public function it_should_get_breakdown(): void
    {
        $expected_response =
            <<<JSON
            {
              "results": [
                {
                    "source": "(Direct / None)",
                    "visitors": 36085,
                    "bounce_rate": 13.2,
                    "pageviews": 10,
                    "visit_duration": 3.4,
                    "events": 25,
                    "visits": 102320,
                    "views_per_visit": 17.2,
                    "conversion_rate": 32.4,
                    "time_on_page": 3.5
                },
                {
                    "source": "Hacker News",
                    "visitors": 36085,
                    "bounce_rate": 13.2,
                    "pageviews": 10,
                    "visit_duration": 3.4,
                    "events": 25,
                    "visits": 102320,
                    "views_per_visit": 17.2,
                    "conversion_rate": 32.4,
                    "time_on_page": 3.5
                }
              ]
            }
            JSON;

        $metric = Metric::create()
            ->addBounceRate()
            ->addPageviews()
            ->addVisitDuration()
            ->addVisitors()
            ->addEvents()
            ->addVisits()
            ->addViewsPerVisit()
            ->addConversionRate()
            ->addTimeOnPage();

        $configuration = $this->mockConfiguration();

        $configuration
            ->getClient()
            ->expects($this->once())
            ->method('get')
            ->with(
                $this->equalTo('v1/stats/breakdown'),
                $this->equalTo([
                    'query' => [
                        'site_id'  => 'example.com',
                        'property' => Property::VISIT_SOURCE,
                        'metrics'  => $metric->toString(),
                    ],
                ])
            )->willReturn($this->mockResponse($expected_response));

        $plausible = new PlausibleAPI($configuration);

        $breakdowns = $plausible->stats()->getBreakdown('example.com', Property::VISIT_SOURCE, [
            'metrics' => $metric->toString()
        ]);

        $this->assertCount(2, $breakdowns);

        $item_1 = $breakdowns->current();

        $this->assertEquals(13.2, $item_1->bounce_rate);
        $this->assertEquals(10, $item_1->pageviews);
        $this->assertEquals(3.4, $item_1->visit_duration);
        $this->assertEquals(36085, $item_1->visitors);
        $this->assertEquals(25, $item_1->events);
        $this->assertEquals(102320, $item_1->visits);
        $this->assertEquals(17.2, $item_1->views_per_visit);
        $this->assertEquals(32.4, $item_1->conversion_rate);
        $this->assertEquals(3.5, $item_1->time_on_page);
    }
}