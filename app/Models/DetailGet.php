<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailGet extends Model
{
    protected $table = 'details_get';
    protected $fillable = [
        'type',
        'item_id',
    ];
    public static function getTopGet(int $limit = 5): array
    {
        $total = static::count();

        if ($total === 0) {
            return [];
        }

        return static::selectRaw("CONCAT(type, ':', item_id) as full_get, COUNT(*) as count")
            ->groupBy('type', 'item_id')
            ->orderBy('count', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($item) use ($total) {
                return [
                    'query' => $item->full_get,
                    'count' => $item->count,
                    'percentage' => round($item->count / $total * 100, 2)
                ];
            })
            ->toArray();
    }
}
