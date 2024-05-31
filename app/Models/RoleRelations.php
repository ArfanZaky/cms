<?php

namespace App\Models;

use App\Traits\HasCache;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\ResponseCache\Facades\ResponseCache;

class RoleRelations extends Model
{
    use HasFactory, SoftDeletes, HasCache;

    protected $table = 'user_role_relations';

    protected $fillable = [
        'admin_id',
        'role_id',
    ];

    public function role()
    {
        return $this->hasOne(Roles::class, 'id', 'role_id');
    }

    public function admin()
    {
        return $this->hasOne(User::class, 'id', 'admin_id');
    }
}
