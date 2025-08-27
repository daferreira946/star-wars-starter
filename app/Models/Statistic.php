<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Statistic extends Model
{
    protected $fillable = [
        'top_queries',
        'top_gets',
        'average_response_time',
        'popular_hour',
        'computed_at'
    ];

    protected $casts = [
        'top_queries' => 'array',
        'top_gets' => 'array',
        'popular_hour' => 'array',
        'computed_at' => 'datetime'
    ];

    public static function getLatest(): ?self
    {
        return static::latest('computed_at')->first();
    }
}
