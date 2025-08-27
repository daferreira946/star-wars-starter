<?php

namespace App\Http\Controllers;

use App\Events\DetailGetPerformed;
use App\Services\StarWarsApi;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class GetAction extends Controller
{
    public function __construct(
        private readonly StarWarsApi $starWarsApi
    ) {
    }

    public function __invoke(string $type, int $id)
    {
        $cacheKey = sprintf('details:%s:%d', $type, $id);

        $data = Cache::remember($cacheKey, 3600, function () use ($type, $id): array {
            try {
                return $this->starWarsApi->getOne($type, $id);
            } catch (Exception $e) {
                Log::error($e->getMessage());
                return [
                    'error' => $e->getMessage()
                ];
            }
        });

        DetailGetPerformed::dispatch($type, $id);

        if (isset($data['error'])) {
            return Inertia::render('details', [
                'data' => []
            ]);
        }

        return Inertia::render('details', [
            'data' => $data,
            'type' => $type,
        ]);
    }
}
