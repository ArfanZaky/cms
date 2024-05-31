<?php

namespace App\Models;

use App\Traits\HasGlobalQueryTrait;
use App\Traits\HasResponse;
use App\Traits\HasTranslation;
use App\Traits\HasCache;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\ResponseCache\Facades\ResponseCache;

class WebContentTranslation extends Model
{
    use HasFactory,HasGlobalQueryTrait, HasResponse, HasTranslation, HasCache;

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
