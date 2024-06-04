<?php

namespace App\Models;

use App\Traits\HasCache;
use App\Traits\HasGlobalQueryTrait;
use App\Traits\HasTranslation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebContentTranslation extends Model
{
    use HasCache,HasFactory, HasGlobalQueryTrait, HasTranslation;

    protected $guarded = [];

    public function content()
    {
        return $this->belongsTo(WebContent::class, 'content_id');
    }

    public function language()
    {
        return $this->belongsTo(Languages::class, 'language_id');
    }
}
