<?php

namespace App\Models;

use App\Traits\GlobalQueryTraits;
use App\Traits\HasResponses;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\ResponseCache\Facades\ResponseCache;

class WebChatbotCategories extends Model
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

    protected $table = 'web_chatbot_categories';

    protected $fillable = [
        'parent',
        'image',
        'image_xs',
        'image_sm',
        'image_md',
        'image_lg',
        'sort',
        'visibility',
        'status',
    ];

    public function translations()
    {
        return $this->hasMany(WebChatbotCategoryTranslations::class, 'chatbot_category_id');
    }

    public function parents()
    {
        return $this->belongsTo(WebChatbotCategories::class, 'parent');
    }

    public function children()
    {
        return $this->hasMany(WebChatbotCategories::class, 'parent');
    }
}
