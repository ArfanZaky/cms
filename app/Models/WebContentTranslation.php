<?php

namespace App\Models;

use App\Traits\HasGlobalQueryTrait;
use App\Traits\HasResponse;
use App\Traits\HasTranslation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\ResponseCache\Facades\ResponseCache;

class WebContentTranslation extends Model
{
    use HasFactory,HasGlobalQueryTrait, HasResponse, HasTranslation;

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

    protected $table = 'web_content_translations';

    protected $fillable = [
        'category_id',
        'language_id',
        'name',
        'sub_name',
        'description',
        'info',
        'redirection',
        'label',
        'target',
        'url_1',
        'label_1',
        'target_1',
        'url_2',
        'label_2',
        'target_2',
        'slug',
        'overview',
        'meta_title',
        'meta_keyword',
        'meta_description',
    ];

    public function category()
    {
        return $this->belongsTo(WebContent::class, 'category_id');
    }

    public function language()
    {
        return $this->belongsTo(Languages::class, 'language_id');
    }
}
