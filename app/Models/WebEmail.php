<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebEmail extends Model
{
    use HasFactory;

    protected $table = 'web_email_relation';

    protected $fillable = [
        'id_category',
        'id_branch',
        'email',
    ];

    public function category()
    {
        return $this->belongsTo(WebArticleCategories::class, 'id_category');
    }

    public function branch()
    {
        return $this->belongsTo(WebArticles::class, 'id_branch');
    }

    public function scopeFilterByCategory($query, $category)
    {
        return $query->where('id_category', $category);
    }
}
