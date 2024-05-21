<?php

namespace App\Models;

use App\Traits\GlobalQueryTraits;
use App\Traits\HasResponses;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\ResponseCache\Facades\ResponseCache;

class WebArticleCategoryRelations extends Model
{
    use GlobalQueryTraits,HasFactory, HasResponses;

    protected static function booted()
    {

        static::addGlobalScope('statusRelation', function (Builder $builder) {
            // only api
            if (request()->is('page/*')) {
                $builder->has('article');
            }
        });

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

    protected $table = 'web_article_category_relations';

    protected $fillable = [
        'article_id',
        'category_id',
    ];

    public function article()
    {
        return $this->belongsTo('App\Models\WebArticles', 'article_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\WebArticleCategories', 'category_id', 'id');
    }
}
