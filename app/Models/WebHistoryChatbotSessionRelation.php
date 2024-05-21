<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebHistoryChatbotSessionRelation extends Model
{
    use HasFactory;

    protected $table = 'web_history_chatbot_session_relation';

    protected $fillable = [
        'id_chatbot',
        'id_session',
    ];

    public function session()
    {
        return $this->belongsTo(WebHistoryChatbotSession::class, 'id_session', 'id');
    }

    public function chatbot()
    {
        return $this->belongsTo(WebHistoryChatbot::class, 'id_chatbot', 'id');
    }
}
