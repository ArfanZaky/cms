<?php

namespace App\Models;

use App\Traits\GlobalQueryTraits;
use App\Traits\HasResponses;
use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebChatbotCategoryTranslations extends Model
{
    use GlobalQueryTraits,HasFactory, HasResponses, HasTranslations;

    protected $table = 'web_chatbot_categories_translations';

    protected $fillable = [
        'chatbot_category_id',
        'language_id',
        'name',
        'description',
        'slug',
        'overview',
        'redirection',
        'meta_title',
        'meta_keyword',
        'meta_description',
    ];

    public function category()
    {
        return $this->belongsTo(WebChatbotCategories::class, 'chatbot_category_id');
    }

    public function language()
    {
        return $this->belongsTo(Languages::class, 'language_id');
    }
}
