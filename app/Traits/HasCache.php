<?php

namespace App\Traits;

use Spatie\ResponseCache\Facades\ResponseCache;

trait HasCache
{
    public static function bootHasCache()
    {
        static::created(function () {
            ResponseCache::clear();
        });

        static::updated(function () {
            ResponseCache::clear();
        });

        static::deleted(function () {
            ResponseCache::clear();
        });
    }
}