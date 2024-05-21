<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\ResponseCache\Facades\ResponseCache;

class WebKurs extends Model
{
    use HasFactory;

    protected static function booted()
    {
        self::created(function () {
            ResponseCache::clear();
        });

        self::updated(function () {
            ResponseCache::clear();
        });

        self::deleted(function () {
            ResponseCache::clear();
        });
    }

    protected $table = 'web_kurs';

    protected $fillable = [
        'country',
        'currency',
        'unit',
        'tt_buy',
        'tt_sell',
        'bn_buy',
        'bn_sell',
        'image',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
