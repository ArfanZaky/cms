<?php

namespace App\Models;

use App\Traits\HasCache;
use App\Traits\HasGlobalQueryTrait;
use App\Traits\HasResponse;
use App\Traits\HasStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use MathPHP\Finance;
use Spatie\ResponseCache\Facades\ResponseCache;
use Spatie\SchemalessAttributes\Casts\SchemalessAttributes;

class WebContent extends Model
{
    use HasFactory,HasGlobalQueryTrait, HasResponse, HasCache, HasStatus;

    protected $guard = [];

    public $casts = [
        'data' => SchemalessAttributes::class,
    ];

    public function scopeWithData(): Builder
    {
        return $this->data->modelScope();
    }

    public function translations()
    {
        return $this->hasMany(WebContentTranslation::class, 'content_id');
    }

    public function parents()
    {
        return $this->belongsTo(WebContent::class, 'parent');
    }

    public function children()
    {
        return $this->hasMany(WebContent::class, 'parent')->orderBy('sort', 'asc');
    }

    public function menu_relation()
    {
        return $this->belongsTo(WebMenu::class, 'id', 'content_id');
    }

    public function breadcrumb($id, $lang)
    {
        $language = _get_languages($lang);
        $data = $this->where('id', $id)->first();
        $result = [];
        if ($data) {
            $result[] = [
                'id' => $data->id,
                'url' => '/'.$lang.'/'.$data->translations->where('language_id', $language)->first()->slug,
                'name' => $data->translations->where('language_id', $language)->first()->name,
            ];
            if ($data->parent) {
                $result = array_merge($this->breadcrumb($data->parent, $lang), $result);
            }
        }

        return $result;
    }
}
