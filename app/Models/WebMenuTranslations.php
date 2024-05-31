<?php

namespace App\Models;

use App\Traits\HasGlobalQueryTrait;
use App\Traits\HasResponse;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\ResponseCache\Facades\ResponseCache;

class WebMenuTranslations extends Model
{
    use HasFactory,HasGlobalQueryTrait, HasResponse;

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

    protected $table = 'web_menu_translations';

    protected $fillable = [
        'menu_id',
        'language_id',
        'name',
        'slug',
        'image_lang',
        'image_sm_lang',
    ];

    public function menu()
    {
        return $this->belongsTo(Menus::class, 'menu_id', 'id');
    }

    public function getCreatedAtAttribute($value)
    {
        return date('d-m-Y H:i:s', strtotime($value));
    }

    public function getUpdatedAtAttribute($value)
    {
        return date('d-m-Y H:i:s', strtotime($value));
    }

    public function getDeletedAtAttribute($value)
    {
        return date('d-m-Y H:i:s', strtotime($value));
    }
}
