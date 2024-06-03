<?php

namespace App\Models;

use App\Traits\HasCache;
use App\Traits\HasGlobalQueryTrait;
use App\Traits\HasResponse;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\ResponseCache\Facades\ResponseCache;

class WebMenuTranslations extends Model
{
    use HasFactory,HasGlobalQueryTrait, HasResponse, HasCache;

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
