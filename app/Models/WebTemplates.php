<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebTemplates extends Model
{
    use HasFactory;

    protected $table = 'web_template';

    protected $fillable = [
        'name',
        'code',
    ];

    public $timestamps = false;

    public function builder()
    {
        return $this->hasMany('App\Models\WebPageBuilders', 'template_id', 'id');
    }
}
