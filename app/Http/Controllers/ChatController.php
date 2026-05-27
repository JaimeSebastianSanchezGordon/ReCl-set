<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Conversation;
use App\Models\Garment;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ChatController extends Controller
{
    /**
     * Muestra la bandeja de entrada con todas las conversaciones del usuario autenticado.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $conversations = Conversation::with(['garment', 'creator', 'recipient'])
            ->where('creator_user_id', $user->id)
            ->orWhere('recipient_user_id', $user->id)
            ->orderByDesc('last_message_at')
            ->get();

        return view('chat.index', compact('conversations'));
    }

    /**
     * Inicia o recupera una conversación sobre una prenda específica.
     */
    public function start(Request $request, Garment $garment)
    {
        $user = $request->user();

        // No permitir chatear con uno mismo
        if ($garment->isOwnedBy($user->id)) {
            return redirect()
                ->route('garments.show', $garment)
                ->with('status', 'No puedes iniciar un chat sobre tu propia prenda.');
        }

        $creatorId = $user->id;
        $recipientId = $garment->user_id;

        // Si la prenda no tiene propietario asignado, no se puede chatear
        if (! $recipientId) {
            return redirect()
                ->route('garments.show', $garment)
                ->with('status', 'Esta prenda no tiene un vendedor asignado.');
        }

        // Buscar una conversación existente para esta prenda entre estos dos participantes
        $conversation = Conversation::where('garment_id', $garment->id)
            ->where(function ($query) use ($creatorId, $recipientId) {
                $query->where(function ($q) use ($creatorId, $recipientId) {
                    $q->where('creator_user_id', $creatorId)
                      ->where('recipient_user_id', $recipientId);
                })->orWhere(function ($q) use ($creatorId, $recipientId) {
                    $q->where('creator_user_id', $recipientId)
                      ->where('recipient_user_id', $creatorId);
                });
            })
            ->first();

        // Si no existe, la creamos
        if (! $conversation) {
            $conversation = Conversation::create([
                'garment_id' => $garment->id,
                'creator_user_id' => $creatorId,
                'recipient_user_id' => $recipientId,
                'last_message_at' => now(),
            ]);
        }

        return redirect()->route('chat.show', $conversation);
    }

    /**
     * Muestra la sala de chat para una conversación específica.
     */
    public function show(Request $request, Conversation $conversation)
    {
        $user = $request->user();

        // Validar participación en el chat
        if ((int) $user->id !== (int) $conversation->creator_user_id && (int) $user->id !== (int) $conversation->recipient_user_id) {
            abort(403, 'No tienes autorización para acceder a esta conversación.');
        }

        $conversation->load(['garment', 'creator', 'recipient']);
        $messages = $conversation->messages()->with('user')->orderBy('created_at', 'asc')->get();

        // Cargar también todas las conversaciones del usuario para la columna izquierda
        $conversations = Conversation::with(['garment', 'creator', 'recipient'])
            ->where('creator_user_id', $user->id)
            ->orWhere('recipient_user_id', $user->id)
            ->orderByDesc('last_message_at')
            ->get();

        return view('chat.show', compact('conversation', 'messages', 'conversations'));
    }

    /**
     * Guarda y transmite un nuevo mensaje dentro de la conversación.
     */
    public function storeMessage(Request $request, Conversation $conversation)
    {
        $user = $request->user();

        // Validar participación en el chat
        if ((int) $user->id !== (int) $conversation->creator_user_id && (int) $user->id !== (int) $conversation->recipient_user_id) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'No autorizado.'], Response::HTTP_FORBIDDEN);
            }
            abort(403, 'No autorizado.');
        }

        $request->validate([
            'body' => ['required', 'string', 'max:2000'],
        ]);

        // Crear el mensaje
        $message = $conversation->messages()->create([
            'user_id' => $user->id,
            'body' => $request->input('body'),
        ]);

        // Actualizar la fecha del último mensaje en la conversación
        $conversation->update([
            'last_message_at' => now(),
        ]);

        $message->load('user');

        // Transmitir en tiempo real
        broadcast(new MessageSent($message))->toOthers();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => [
                    'id' => $message->id,
                    'body' => $message->body,
                    'user_id' => $message->user_id,
                    'formatted_time' => $message->created_at->format('H:i'),
                    'user_name' => $message->user->name,
                ],
            ]);
        }

        return redirect()->route('chat.show', $conversation);
    }

    /**
     * Elimina una conversación y sus mensajes asociados.
     */
    public function destroy(Request $request, Conversation $conversation)
    {
        $user = $request->user();

        // Validar participación en el chat
        if ((int) $user->id !== (int) $conversation->creator_user_id && (int) $user->id !== (int) $conversation->recipient_user_id) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'No autorizado.'], Response::HTTP_FORBIDDEN);
            }
            abort(403, 'No autorizado.');
        }

        // Eliminar de la base de datos (mensajes se borrarán por cascada en la BD)
        $conversation->delete();

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Conversación eliminada.']);
        }

        return redirect()
            ->route('chat.index')
            ->with('status', 'Conversación eliminada correctamente.');
    }
}
