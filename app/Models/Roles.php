<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Roles extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'user_roles';

    protected $fillable = [
        'name',
    ];

    public function permissions()
    {
        return $this->hasMany(PermissionRelations::class, 'role_id', 'id');
    }
}
