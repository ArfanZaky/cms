<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\ResponseCache\Facades\ResponseCache;
use Spatie\SchemalessAttributes\Casts\SchemalessAttributes;

class WebPageBuilders extends Model
{
    use HasFactory;

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

    public $casts = [
        'description' => SchemalessAttributes::class,
    ];

    public function scopeWithExtraAttributes(): Builder
    {
        return $this->description->modelScope();
    }

    protected $table = 'web_page_builders';

    protected $fillable = [
        'page_id',
        'type',
        'template_id',
        'model_type',
        'model_id',
        'description',
        'language_id',
    ];

    public $timestamps = false;

    public function page()
    {
        return $this->belongsTo('App\Models\WebPages', 'page_id', 'id');
    }

    public function template()
    {
        return $this->belongsTo('App\Models\WebTemplates', 'template_id', 'id');
    }

    public function scopeSearchData($query, $searchTerm)
    {
        return $query->whereRaw('MATCH(descriptions) AGAINST(? IN BOOLEAN MODE)', [$searchTerm]);
    }
}
