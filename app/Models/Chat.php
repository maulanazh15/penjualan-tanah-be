<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Chat extends Model
{
    use HasFactory;

    public function participants() : HasMany {
        return $this->hasMany(ChatParticipant::class, 'chat_id');
    }

    public function messages() : HasMany {
        return $this->hasMany(ChatMessage::class, 'chat_id');
    }

    public function lastMessage() {
        return $this->hasOne(ChatMessage::class, 'chat_id')->latest('updated_at');
    }
}
