<?php

namespace App\Models;

use App\Traits\HasCache;
use App\Traits\HasGlobalQueryTrait;
use App\Traits\HasStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\SchemalessAttributes\Casts\SchemalessAttributes;

class WebContacts extends Model
{
    use HasCache,HasFactory, HasGlobalQueryTrait, HasStatus;

    protected $guarded = [];

    public function scopeWithExtraAttributes(): Builder
    {
        return $this->logs->modelScope();
    }

    protected $hidden = [
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'deleted_at' => 'datetime:Y-m-d H:i:s',
        'logs' => SchemalessAttributes::class,
    ];

    public function content()
    {
        return $this->belongsTo(WebContent::class, 'content_id');
    }
}
