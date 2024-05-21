<?php

namespace App\Models;

use App\Traits\GlobalQueryTraits;
use App\Traits\HasResponses;
use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\ResponseCache\Facades\ResponseCache;

class WebArticleTranslations extends Model
{
    use GlobalQueryTraits,HasFactory, HasResponses, HasTranslations;

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

    protected $table = 'web_article_translations';

    protected $fillable = [
        'article_id',
        'language_id',
        'name',
        'sub_name',
        'slug',
        'overview',
        'description',
        'info',
        'url_1',
        'url_2',
        'redirection',
        'meta_title',
        'meta_description',
        'meta_keyword',
    ];

    public function scopeSearchData($query, $searchTerm)
    {
        return $query->whereRaw('MATCH(description) AGAINST(? IN BOOLEAN MODE)', [$searchTerm]);
    }

    public function article()
    {
        return $this->belongsTo('App\Models\WebArticles', 'article_id', 'id');
    }

    public function language()
    {
        return $this->belongsTo('App\Models\Languages', 'language_id', 'id');
    }
}
