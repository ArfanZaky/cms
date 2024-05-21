<?php

namespace App\Models;

use App\Services\ApiService;
use App\Traits\GlobalQueryTraits;
use App\Traits\HasResponses;
use Helper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\ResponseCache\Facades\ResponseCache;

class WebMenus extends Model
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

    protected $table = 'web_menus';

    protected $fillable = [
        'menu_id',
        'admin_id',
        'category_id',
        'catalog_id',
        'gallery_id',
        'parent',
        'target',
        'image',
        'image_sm',
        'parameter',
        'url',
        'sort',
        'visibility',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function translations()
    {
        return $this->hasMany('App\Models\WebMenuTranslations', 'menu_id', 'id');
    }

    public function parent()
    {
        return $this->hasOne('App\Models\WebMenus', 'id', 'parent');
    }

    public function parentData()
    {
        return $this->hasOne('App\Models\WebMenus', 'id', 'parent');
    }

    public function children()
    {
        return $this->hasMany('App\Models\WebMenus', 'parent', 'id');
    }

    public function scopeWithTranslationsForLanguage($query, $language_id)
    {
        return $query->with(['translations' => function ($q) use ($language_id) {
            $q->where('language_id', $language_id);
        }]);
    }

    /**
     * Get the menu items as a tree structure
     *
     * @param  int  $language_id
     * @return array
     */
    public function getMenuTree($language_id)
    {
        $menus = $this->withTranslationsForLanguage($language_id)->get();
        $apiService = new ApiService;
        $menus = $apiService->toArray($menus, true);
        $menus = Helper::parent($menus);
        $menus = Helper::tree($menus);
        $menus = Helper::obj($menus);

        return $menus;
    }

    // scope
    public function scopeMenuByLang($query, $language)
    {
        return $query->with(['translations' => function ($q) use ($language) {
            $q->where('language_id', $language);
        }]);
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
