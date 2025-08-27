<?php

namespace Tests\Unit\Models;

use App\Models\Statistic;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StatisticTest extends TestCase
{
    use RefreshDatabase;

    public function test_statistic_model_has_correct_fillable_attributes()
    {
        $statistic = new Statistic();

        $expected = [
            'top_queries',
            'top_gets',
            'average_response_time',
            'popular_hour',
            'computed_at'
        ];

        $this->assertEquals($expected, $statistic->getFillable());
    }

    public function test_statistic_model_has_correct_casts()
    {
        $statistic = new Statistic();

        $casts = $statistic->getCasts();

        $this->assertEquals('array', $casts['top_queries']);
        $this->assertEquals('array', $casts['top_gets']);
        $this->assertEquals('array', $casts['popular_hour']);
        $this->assertEquals('datetime', $casts['computed_at']);
    }

    public function test_get_latest_returns_most_recent_statistic()
    {
        Statistic::create([
            'top_queries' => ['luke'],
            'top_gets' => [],
            'average_response_time' => 100,
            'popular_hour' => ['hour' => 12],
            'computed_at' => Carbon::parse('2023-01-01 10:00:00')
        ]);

        $newer = Statistic::create([
            'top_queries' => ['vader'],
            'top_gets' => [],
            'average_response_time' => 200,
            'popular_hour' => ['hour' => 14],
            'computed_at' => Carbon::parse('2023-01-01 12:00:00')
        ]);

        $result = Statistic::getLatest();

        $this->assertNotNull($result);
        $this->assertEquals($newer->id, $result->id);
        $this->assertEquals(['vader'], $result->top_queries);
        $this->assertEquals(200, $result->average_response_time);
    }

    public function test_get_latest_returns_null_when_no_statistics_exist()
    {
        $result = Statistic::getLatest();

        $this->assertNull($result);
    }

    public function test_array_attributes_are_properly_cast()
    {
        $data = [
            'top_queries' => ['luke', 'vader'],
            'top_gets' => [1, 2, 3],
            'average_response_time' => 150.5,
            'popular_hour' => ['hour' => 14, 'count' => 25],
            'computed_at' => Carbon::now()
        ];

        $statistic = Statistic::create($data);

        $this->assertIsArray($statistic->top_queries);
        $this->assertIsArray($statistic->top_gets);
        $this->assertIsArray($statistic->popular_hour);
        $this->assertEquals(['luke', 'vader'], $statistic->top_queries);
        $this->assertEquals([1, 2, 3], $statistic->top_gets);
        $this->assertEquals(['hour' => 14, 'count' => 25], $statistic->popular_hour);
    }

    public function test_computed_at_is_properly_cast_to_datetime()
    {
        $dateTime = '2023-01-01 12:00:00';

        $statistic = Statistic::create([
            'top_queries' => [],
            'top_gets' => [],
            'average_response_time' => 0,
            'popular_hour' => [],
            'computed_at' => $dateTime
        ]);

        $this->assertInstanceOf(Carbon::class, $statistic->computed_at);
        $this->assertEquals('2023-01-01 12:00:00', $statistic->computed_at->format('Y-m-d H:i:s'));
    }
}
