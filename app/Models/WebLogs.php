<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebLogs extends Model
{
    use HasFactory;

    protected $table = 'web_logs';

    protected $fillable = [
        'ip',
        'admin_id',
        'table_id',
        'name',
        'json',
        'status',
        'updated_at',
        'created_at',
    ];

    // user
    public function user()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function getCreatedAtAttribute($date)
    {
        return Carbon::parse($date)->addHours(7)->format('d-m-Y H:i:s');
    }

    public function getUpdatedAtAttribute($date)
    {
        return Carbon::parse($date)->addHours(7)->format('d-m-Y H:i:s');
    }
}
