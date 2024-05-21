<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebHistoryChatbotSession extends Model
{
    use HasFactory;

    protected $table = 'web_history_chatbot_session';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'gender',
    ];

    public function relation()
    {
        return $this->hasMany(WebHistoryChatbotSessionRelation::class, 'id_session', 'id');
    }
}
