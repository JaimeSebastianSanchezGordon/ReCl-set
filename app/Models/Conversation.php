<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'garment_id',
    'creator_user_id',
    'recipient_user_id',
    'last_message_at',
])]
class Conversation extends Model
{
    /**
     * Relación con la prenda asociada a la conversación.
     */
    public function garment(): BelongsTo
    {
        return $this->belongsTo(Garment::class);
    }

    /**
     * Relación con el usuario creador de la conversación.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_user_id');
    }

    /**
     * Relación con el usuario receptor de la conversación.
     */
    public function recipient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recipient_user_id');
    }

    /**
     * Relación con los mensajes asociados a esta conversación.
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    /**
     * Obtiene el otro usuario de la conversación.
     */
    public function getOtherUser(User $user): User
    {
        return (int) $user->id === (int) $this->creator_user_id
            ? $this->recipient
            : $this->creator;
    }

    /**
     * Atributos que deben ser casteados.
     */
    protected function casts(): array
    {
        return [
            'last_message_at' => 'datetime',
        ];
    }
}
