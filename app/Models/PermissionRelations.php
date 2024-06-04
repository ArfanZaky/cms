<?php

namespace App\Models;

use App\Traits\HasCache;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermissionRelations extends Model
{
    use HasCache, HasFactory;

    protected $table = 'user_permission_relations';

    protected $fillable = [
        'role_id',
        'permission_id',
        'content_id',
        'page_id',
    ];

    public $timestamps = false;

    public function permission()
    {
        return $this->belongsTo(Permissions::class, 'permission_id');
    }
}
