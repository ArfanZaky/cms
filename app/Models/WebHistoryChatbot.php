<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\SchemalessAttributes\Casts\SchemalessAttributes;

class WebHistoryChatbot extends Model
{
    use HasFactory;

    protected $table = 'web_history_chatbot';

    public $timestamps = false;

    public $casts = [
        'json' => SchemalessAttributes::class,
    ];

    public function scopeWithJson(): Builder
    {
        return $this->json->modelScope();
    }

    protected $fillable = [
        'close_chat',
        'json',
    ];

    public function relation()
    {
        return $this->hasMany(WebHistoryChatbotSessionRelation::class, 'id_chatbot', 'id');
    }
}
