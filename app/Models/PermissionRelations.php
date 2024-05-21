<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\ResponseCache\Facades\ResponseCache;

class PermissionRelations extends Model
{
    use HasFactory;

    protected $table = 'user_permission_relations';

    protected $fillable = [
        'role_id',
        'permission_id',
        'category_id',
        'page_id',
    ];

    public $timestamps = false;

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

    public function permission()
    {
        return $this->belongsTo(Permissions::class, 'permission_id');
    }
}
