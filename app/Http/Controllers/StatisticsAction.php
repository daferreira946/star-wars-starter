<?php

namespace App\Http\Controllers;

use App\Models\Statistic;
use Illuminate\Http\JsonResponse;

class StatisticsAction extends Controller
{
    public function __invoke(): JsonResponse
    {
        $statistics = Statistic::getLatest();

        if (!$statistics) {
            return response()->json(
                [
                    'message' => 'No statistics found'
                ],
                404
            );
        }

        return response()->json(
            [
                'data' => [
                    'top_queries' => $statistics->top_queries,
                    'average_response_time_ms' => $statistics->average_response_time,
                    'most_popular_hour' => $statistics->popular_hour,
                    'computed_at' => $statistics->computed_at->toISOString(),
                ],
            ]
        );
    }
}
