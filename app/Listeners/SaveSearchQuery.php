<?php

namespace App\Listeners;

use App\Events\SearchQueryPerformed;
use App\Models\SearchQuery;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SaveSearchQuery implements ShouldQueue
{
    use InteractsWithQueue;
    
    /**
     * Handle the event.
     */
    public function handle(SearchQueryPerformed $event): void
    {
        SearchQuery::create(
            [
                'type' => $event->type,
                'query' => $event->query,
                'response_time' => $event->responseTime
            ]
        );
    }
}
