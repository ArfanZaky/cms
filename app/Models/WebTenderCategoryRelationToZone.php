<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\ResponseCache\Facades\ResponseCache;

class WebTenderCategoryRelationToZone extends Model
{
    use HasFactory;

    protected $table = 'web_tender_category_relation_to_zone';

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

    protected $fillable = [
        'id_tender',
        'category_zone',
    ];

    public $timestamps = false;

    public function tender()
    {
        return $this->belongsTo('App\Models\WebArticles', 'id_tender', 'id');
    }

    public function zone()
    {
        return $this->belongsTo('App\Models\WebArticleCategories', 'category_zone', 'id');
    }
}
