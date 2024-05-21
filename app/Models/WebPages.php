<?php

namespace App\Models;

use App\Traits\GlobalQueryTraits;
use App\Traits\HasResponses;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\ResponseCache\Facades\ResponseCache;

class WebPages extends Model
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

    protected $table = 'web_pages';

    protected $fillable = [
        'admin_id',
        'menu_id',
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

    public function translations()
    {
        return $this->hasMany('App\Models\WebPagesTranslations', 'page_id', 'id');
    }

    public function builder()
    {
        return $this->hasMany('App\Models\WebPageBuilders', 'page_id', 'id');
    }

    public function SearchData($searchTerm)
    {
        $union = $this->builder()->searchData($searchTerm)->pluck('page_id');
        $union = $union->union($this->translations()->searchData($searchTerm)->pluck('page_id'));

        return $union;
    }

    public function admin()
    {
        return $this->belongsTo('App\Models\Admins', 'admin_id', 'id');
    }

    public function menu_relation()
    {
        return $this->belongsTo('App\Models\WebMenus', 'menu_id', 'id');
    }

    public function getPublishAtAttribute($date)
    {
        return Carbon::parse($date)->format('d-m-Y H:i:s');
    }

    public function getUnpublishAtAttribute($date)
    {
        return Carbon::parse($date)->format('d-m-Y H:i:s');
    }

    public function getCreatedAtAttribute($date)
    {
        return Carbon::parse($date)->format('d-m-Y H:i:s');
    }

    public function getUpdatedAtAttribute($date)
    {
        return Carbon::parse($date)->format('d-m-Y H:i:s');
    }

    public function getDeletedAtAttribute($date)
    {
        return Carbon::parse($date)->format('d-m-Y H:i:s');
    }
}
