<?php

namespace App\Jobs;

use App\Models\DetailGet;
use App\Models\SearchQuery;
use App\Models\Statistic;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ComputeStatistics implements ShouldQueue
{
    use Queueable, Dispatchable, InteractsWithQueue, SerializesModels;


    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $topQueries = SearchQuery::getTopQueries();
        $topGets = DetailGet::getTopGet();
        $averageResponseTime = SearchQuery::getAverageResponseTime();
        $popularHour = SearchQuery::getMostPopularHour();

        Statistic::create(
            [
                'top_queries' => $topQueries,
                'top_gets' => $topGets,
                'average_response_time' => $averageResponseTime,
                'popular_hour' => $popularHour,
                'computed_at' => now(),
            ]
        );

        $latest = Statistic::latest('computed_at')->skip(10)->pluck('id');
        Statistic::whereIn('id', $latest)->delete();
    }
}
