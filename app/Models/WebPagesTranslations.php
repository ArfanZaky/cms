<?php

namespace App\Models;

use App\Traits\GlobalQueryTraits;
use App\Traits\HasResponses;
use App\Traits\HasTranslations;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\ResponseCache\Facades\ResponseCache;

class WebPagesTranslations extends Model
{
    use GlobalQueryTraits ,HasFactory, HasResponses, HasTranslations;

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

    protected $table = 'web_page_translations';

    protected $fillable = [
        'page_id',
        'language_id',
        'name',
        'sub_name',
        'slug',
        'description',
        'overview',
        'meta_title',
        'meta_description',
        'meta_keyword',
    ];

    public function page()
    {
        return $this->belongsTo('App\Models\WebPages', 'page_id', 'id');
    }

    public function language()
    {
        return $this->belongsTo('App\Models\Languages', 'language_id', 'id');
    }

    public function getCreatedAtAttribute($date)
    {
        return Carbon::parse($date)->format('d.m.Y');
    }

    public function getUpdatedAtAttribute($date)
    {
        return Carbon::parse($date)->format('d.m.Y');
    }

    public function getDeletedAtAttribute($date)
    {
        return Carbon::parse($date)->format('d.m.Y');
    }
}
