<?php

namespace App\Models;

use App\Traits\GlobalQueryTraits;
use App\Traits\HasResponses;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\ResponseCache\Facades\ResponseCache;

class WebArticles extends Model
{
    use GlobalQueryTraits,HasFactory, HasResponses;

    protected static function booted()
    {
        static::addGlobalScope('status', function (Builder $builder) {
            // only api
            if (request()->is('page/*')) {
                $builder->where('status', 1);
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

    protected $table = 'web_articles';

    protected $fillable = [
        'admin_id',
        'gallery_id',
        'view',
        'custom',
        'attachment',
        'video',
        'image',
        'image_xs',
        'image_sm',
        'image_md',
        'image_lg',
        'publish_at',
        'unpublish_at',
        'visibility',
        'sort',
        'status',
    ];

    // protected $casts = [
    //     'created_at' => 'datetime:Y-m-d H:i:s',
    //     'updated_at' => 'datetime:Y-m-d H:i:s',
    //     'deleted_at' => 'datetime:Y-m-d H:i:s',
    // ];

    public function translations()
    {
        return $this->hasMany('App\Models\WebArticleTranslations', 'article_id', 'id');
    }

    public function categories()
    {
        return $this->belongsToMany('App\Models\WebArticleCategories', 'web_article_category_relations', 'article_id', 'category_id')->join('web_article_category_translations', 'web_article_category_translations.category_id', '=', 'web_article_category_relations.category_id')->where('web_article_category_translations.language_id', 1)
            ->select('web_article_categories.*', 'web_article_category_translations.name as category_name')->where('web_article_category_translations.deleted_at', null);
    }

    public function categoryArticles()
    {
        return $this->belongsToMany('App\Models\WebArticleCategories', 'web_article_category_relations', 'article_id', 'category_id');
    }

    public function admin()
    {
        return $this->belongsTo('App\Models\Admins', 'admin_id', 'id');
    }

    public function relationTender()
    {
        return $this->hasMany('App\Models\WebTenderCategoryRelationToZone', 'id_tender');
    }

    public function email()
    {
        return $this->hasMany(WebEmail::class, 'id_branch');
    }

    public function getPublishAtAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }

    public function getUnpublishAtAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }
}
