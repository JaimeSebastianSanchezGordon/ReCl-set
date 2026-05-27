<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'conversation_id',
    'user_id',
    'body',
    'read_at',
])]
class Message extends Model
{
    /**
     * Relación con la conversación a la que pertenece el mensaje.
     */
    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    /**
     * Relación con el usuario remitente del mensaje.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Atributos que deben ser casteados.
     */
    protected function casts(): array
    {
        return [
            'read_at' => 'datetime',
        ];
    }
}
