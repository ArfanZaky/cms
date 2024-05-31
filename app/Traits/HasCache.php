<?php

namespace App\Traits;

use Spatie\ResponseCache\Facades\ResponseCache;

trait HasCache
{
    public static function bootHasCache()
    {
        static::created(function () {
            ResponseCache::clearCache();
        });

        static::updated(function () {
            ResponseCache::clearCache();
        });

        static::deleted(function () {
            ResponseCache::clearCache();
        });
    }
}
