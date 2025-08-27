<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SearchQuery extends Model
{
    protected $fillable = [
        'type',
        'query',
        'response_time'
    ];

    protected $casts = [
        'response_time' => 'float',
        'created_at' => 'datetime'
    ];

    public static function getTopQueries(int $limit = 5): array
    {
        $total = static::count();

        if ($total === 0) {
            return [];
        }

        return static::selectRaw("CONCAT(type, ':', query) as full_query, COUNT(*) as count")
            ->groupBy('type', 'query')
            ->orderBy('count', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($item) use ($total) {
                return [
                    'query' => $item->full_query,
                    'count' => $item->count,
                    'percentage' => round($item->count / $total * 100, 2)
                ];
            })
            ->toArray();
    }

    public static function getAverageResponseTime(): float
    {
        return static::avg('response_time') ?? 0;
    }

    public static function getMostPopularHour(): array
    {
        return static::selectRaw('EXTRACT(HOUR FROM created_at) as hour, COUNT(*) as count')
            ->groupBy('hour')
            ->orderBy('count', 'desc')
            ->first()
            ?->toArray() ?? ['hour' => 0, 'count' => 0];
    }
}
