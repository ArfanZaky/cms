<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasStatus
{
    public static function bootHasStatus()
    {
        static::addGlobalScope('status', function (Builder $builder) {
            if (request()->is('page/*')) {
                $builder->where('status', 1);
            }
        });
    }
}
