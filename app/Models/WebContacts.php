<?php

namespace App\Models;

use App\Traits\HasGlobalQueryTrait;
use App\Traits\HasResponse;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\ResponseCache\Facades\ResponseCache;
use Spatie\SchemalessAttributes\Casts\SchemalessAttributes;

class WebContacts extends Model
{
    use HasFactory,HasGlobalQueryTrait, HasResponse;

    protected $table = 'web_contacts';

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

    public function scopeWithExtraAttributes(): Builder
    {
        return $this->logs->modelScope();
    }

    protected $fillable = [
        'category_id',
        'full_name',
        'citizen',
        'nik',
        'address',
        'place_of_birth',
        'date_of_birth',
        'passport',
        'email',
        'phone',
        'existing_customer',
        'amount',
        'state',
        'district',
        'branch_name',
        'company',
        'subject',
        'description',
        'reference_no',
        'status',
        'option',
        'visibility',
        'status_form',
        'logs',
    ];

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

    public function category()
    {
        return $this->belongsTo(WebContent::class, 'category_id');
    }
}
