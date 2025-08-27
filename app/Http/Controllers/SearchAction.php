<?php

namespace App\Http\Controllers;

use App\Events\SearchQueryPerformed;
use App\Http\Requests\SearchRequest;
use App\Services\StarWarsApi;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SearchAction extends Controller
{
    public function __construct(
        private readonly StarWarsApi $starWarsApi
    ) {
    }
    public function __invoke(SearchRequest $request)
    {
        $validated = $request->validated();
        $type = $validated['type'];
        $query = $validated['query'];
        $cacheKey = sprintf('search:%s:%s', $type, $query);

        $startTime = microtime(true);
        $results = Cache::remember($cacheKey, 3600, function () use ($type, $query): array {
            try {
                return $this->starWarsApi->query(
                    $type,
                    $query
                );
            } catch (Exception $e) {
                Log::error($e->getMessage());
                return [
                    'error' => $e->getMessage()
                ];
            }
        });

        $responseTime = (microtime(true) - $startTime) * 1000;
        SearchQueryPerformed::dispatch($type, $query, $responseTime);

        if (isset($results['error'])) {
            return redirect()->back()->withErrors(
                [
                    'error' => $results['error']
                ]
            );
        }

        return redirect()->back()->with(
            [
                'searchResults' => $results,
                'searchQuery' => $validated['query'],
                'searchType' => $validated['type']
            ]
        );
    }
}
