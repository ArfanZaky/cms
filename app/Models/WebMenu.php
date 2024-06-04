<?php

namespace App\Models;

use App\Services\ApiService;
use App\Traits\HasCache;
use App\Traits\HasGlobalQueryTrait;
use App\Traits\HasStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebMenu extends Model
{
    use HasCache,HasFactory, HasGlobalQueryTrait, HasStatus;

    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function translations()
    {
        return $this->hasMany(WebMenuTranslations::class, 'menu_id', 'id');
    }

    public function parent()
    {
        return $this->hasOne(WebMenu::class, 'id', 'parent');
    }

    public function parentData()
    {
        return $this->hasOne(WebMenu::class, 'id', 'parent');
    }

    public function children()
    {
        return $this->hasMany(WebMenu::class, 'parent', 'id');
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
        $menus = \App\Helper\Helper::parent($menus);
        $menus = \App\Helper\Helper::tree($menus);
        $menus = \App\Helper\Helper::obj($menus);

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
