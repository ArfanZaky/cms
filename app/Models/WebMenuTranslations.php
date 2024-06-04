<?php

namespace App\Models;

use App\Traits\HasCache;
use App\Traits\HasGlobalQueryTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebMenuTranslations extends Model
{
    use HasCache,HasFactory, HasGlobalQueryTrait;

    protected $guarded = [];

    public function menu()
    {
        return $this->belongsTo(WebMenu::class, 'menu_id', 'id');
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
