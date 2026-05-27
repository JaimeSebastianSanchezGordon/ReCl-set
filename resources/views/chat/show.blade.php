@extends('layouts.app')

@section('title', 'Chat con ' . $conversation->getOtherUser(auth()->user())->name . ' — ReClóset')

@section('content')
<style>
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(12px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .animate-fade-in-up {
        animation: fadeInUp 0.25s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
</style>

<div class="mx-auto max-w-4xl flex flex-col h-[calc(100vh-12rem)] min-h-[500px]">
    {{-- ===== Botón de regreso y barra de título ===== --}}
    <div class="mb-4 flex items-center justify-between">
        <a class="text-sm font-bold text-stone-600 hover:text-stone-850 transition flex items-center gap-1.5" href="{{ route('chat.index') }}">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
            </svg>
            Volver a Conversaciones
        </a>
        <span class="text-xs text-stone-400 font-semibold bg-stone-100/80 rounded-lg px-2 py-1">Sala de Negociación</span>
    </div>

    {{-- ===== Contenedor principal de Chat ===== --}}
    <div class="flex flex-1 flex-col overflow-hidden rounded-3xl border border-stone-200/80 bg-white shadow-xl shadow-stone-100/40">
        
        {{-- Cabecera: Info de la prenda, el otro usuario y eliminar chat --}}
        @php
            $otherUser = $conversation->getOtherUser(auth()->user());
            $garment = $conversation->garment;
        @endphp
        <div class="border-b border-stone-150 bg-stone-50/70 p-4">
            <div class="flex flex-wrap items-center justify-between gap-4">
                
                {{-- Info del participante --}}
                <div class="flex items-center gap-3">
                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-550 to-emerald-700 font-bold text-white shadow-md shadow-emerald-600/10">
                        {{ substr($otherUser->name, 0, 1) }}
                    </div>
                    <div>
                        <h3 class="text-sm font-extrabold text-stone-900">{{ $otherUser->name }}</h3>
                        <div class="flex items-center gap-1.5 mt-0.5">
                            <span class="relative flex h-1.5 w-1.5">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-450 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-emerald-500"></span>
                            </span>
                            <span class="text-[11px] text-stone-400 font-semibold">En línea para negociar</span>
                        </div>
                    </div>
                </div>

                {{-- Mini Ficha de la Prenda --}}
                <div class="flex items-center gap-2">
                    @if ($garment)
                        <a
                            href="{{ route('garments.show', $garment) }}"
                            class="flex items-center gap-3 rounded-2xl border border-stone-200 bg-white px-3 py-2 text-left hover:border-stone-300 hover:shadow-sm transition shrink-0 max-w-[280px]"
                        >
                            <div class="h-9 w-9 shrink-0 overflow-hidden rounded-lg bg-stone-100 flex items-center justify-center border border-stone-100">
                                @if ($garment->image_path)
                                    <img
                                        src="{{ asset('storage/' . $garment->image_path) }}"
                                        alt="{{ $garment->name }}"
                                        class="h-full w-full object-cover"
                                    >
                                @else
                                    <svg class="h-5 w-5 text-stone-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0 0 22.5 18.75V5.25A2.25 2.25 0 0 0 20.25 3H3.75A2.25 2.25 0 0 0 1.5 5.25v13.5A2.25 2.25 0 0 0 3.75 21Z" />
                                    </svg>
                                @endif
                            </div>
                            <div class="min-w-0 pr-1">
                                <h4 class="text-xs font-bold text-stone-900 truncate leading-tight">{{ $garment->name }}</h4>
                                <p class="text-[10px] font-bold text-emerald-700 mt-0.5">
                                    ${{ number_format((float) $garment->price, 2) }}
                                    <span class="mx-1 text-stone-300 font-normal">|</span>
                                    <span class="font-bold text-[9px]">
                                        {{ $garment->statusLabel() }}
                                    </span>
                                </p>
                            </div>
                        </a>
                    @else
                        <div class="text-[11px] italic text-stone-400 bg-stone-100 rounded-xl px-3 py-2 border border-stone-150">
                            Prenda no disponible
                        </div>
                    @endif

                    {{-- Botón de eliminar chat --}}
                    <button
                        type="button"
                        onclick="toggleDeleteModal(true)"
                        class="inline-flex h-10 w-10 items-center justify-center rounded-2xl border border-stone-200 bg-white text-stone-400 hover:bg-rose-50 hover:text-rose-600 hover:border-rose-100 transition duration-200"
                        title="Eliminar esta conversación"
                    >
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        {{-- Área de Mensajes --}}
        <div
            id="messages-container"
            class="flex-1 overflow-y-auto bg-stone-50/50 p-5 space-y-4 flex flex-col"
        >
            @if ($messages->isEmpty())
                <div id="no-messages-prompt" class="my-auto text-center p-8">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600 shadow-inner">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 20.25c4.97 0 9-3.694 9-8.25s-4.03-8.25-9-8.25S3 7.444 3 12c0 2.104.859 4.023 2.273 5.48.432.447.74 1.04.586 1.641l-.318 1.235c-.027.106-.006.218.056.303A.358.358 0 0 0 5.897 21c.3 0 .591-.072.859-.21l1.75-.905c.148-.077.307-.104.468-.105 1.018.15 2.072.22 3.149.22H12Z" />
                        </svg>
                    </div>
                    <h4 class="mt-4 text-base font-bold text-stone-900">¡Inicia la conversación!</h4>
                    <p class="mt-2 text-xs text-stone-450 leading-relaxed max-w-xs mx-auto">
                        Hazle una pregunta a {{ $otherUser->name }} sobre su prenda para resolver cualquier duda.
                    </p>
                </div>
            @else
                @php
                    $lastDate = null;
                @endphp
                @foreach ($messages as $message)
                    @php
                        $isMe = (int) $message->user_id === (int) auth()->id();
                        
                        // Generar cabeceras de fecha premium
                        $messageDate = $message->created_at->format('Y-m-d');
                        $dateLabel = '';
                        if ($lastDate !== $messageDate) {
                            if ($message->created_at->isToday()) {
                                $dateLabel = 'Hoy';
                            } elseif ($message->created_at->isYesterday()) {
                                $dateLabel = 'Ayer';
                            } else {
                                $dateLabel = $message->created_at->translatedFormat('j \d\e F, Y');
                            }
                            $lastDate = $messageDate;
                        }
                    @endphp

                    @if ($dateLabel)
                        <div class="flex justify-center my-2">
                            <span class="rounded-full bg-stone-200/60 px-3 py-1 text-[10px] font-extrabold text-stone-500 uppercase tracking-wider">
                                {{ $dateLabel }}
                            </span>
                        </div>
                    @endif

                    <div class="flex flex-col {{ $isMe ? 'items-end' : 'items-start' }}">
                        <div class="max-w-[72%] rounded-2xl px-4 py-2.5 shadow-sm text-sm leading-relaxed
                            {{ $isMe
                                ? 'bg-gradient-to-br from-emerald-600 to-emerald-700 text-white rounded-tr-none shadow-emerald-600/5'
                                : 'bg-white text-stone-850 border border-stone-200/80 rounded-tl-none' }}"
                        >
                            <p class="whitespace-pre-line">{{ $message->body }}</p>
                        </div>
                        <span class="mt-1 text-[9px] text-stone-400 font-medium px-1.5">
                            {{ $message->created_at->format('H:i') }}
                        </span>
                    </div>
                @endforeach
            @endif
        </div>

        {{-- Formulario para Enviar Mensaje --}}
        <div class="border-t border-stone-150 bg-white p-4">
            <form
                id="chat-form"
                method="POST"
                action="{{ route('chat.message.store', $conversation) }}"
                class="flex items-center gap-2"
            >
                @csrf
                <input
                    type="text"
                    name="body"
                    id="message-input"
                    placeholder="Preguntar al vendedor..."
                    required
                    autocomplete="off"
                    class="flex-1 rounded-2xl border border-stone-200 bg-stone-50 px-4 py-3.5 text-sm text-stone-900 placeholder-stone-400 focus:border-emerald-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-emerald-500 transition duration-150"
                >
                <button
                    type="submit"
                    id="send-button"
                    class="inline-flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-emerald-600 text-white shadow-md shadow-emerald-600/10 transition hover:bg-emerald-700 hover:shadow-emerald-750/20 active:scale-95 focus:outline-none"
                >
                    <svg class="h-5.5 w-5.5 rotate-90 transform" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" />
                    </svg>
                </button>
            </form>
        </div>
    </div>
</div>

{{-- ===== Modal de confirmación de eliminación (específico de la sala) ===== --}}
<div
    id="delete-chat-modal"
    class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm transition-opacity duration-300"
    onclick="if(event.target===this) toggleDeleteModal(false)"
>
    <div class="mx-4 w-full max-w-md transform scale-95 opacity-0 transition-all duration-300 rounded-3xl border border-stone-200 bg-white p-6 shadow-2xl flex flex-col gap-4" id="delete-modal-card">
        <div class="flex items-start gap-4">
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-rose-50 text-rose-600 shadow-inner">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-bold text-stone-900">¿Eliminar esta conversación?</h3>
                <p class="mt-2 text-sm text-stone-600 leading-relaxed">
                    ¿Estás seguro de que deseas eliminar permanentemente la conversación con <strong>{{ $otherUser->name }}</strong> sobre la prenda <strong>{{ $garment ? $garment->name : 'Prenda eliminada' }}</strong>?
                </p>
                <p class="mt-1 text-xs text-rose-500 font-semibold">
                    Esta acción es irreversible y eliminará todo el historial de chats en las cuentas de ambos usuarios.
                </p>
            </div>
        </div>
        <div class="mt-2 flex items-center justify-end gap-3">
            <button
                type="button"
                class="rounded-xl px-4 py-2.5 text-sm font-semibold text-stone-600 hover:bg-stone-50 hover:text-stone-800 transition active:scale-95"
                onclick="toggleDeleteModal(false)"
            >
                Cancelar
            </button>
            <form method="POST" action="{{ route('chat.destroy', $conversation) }}">
                @csrf
                @method('DELETE')
                <button
                    type="submit"
                    class="inline-flex items-center rounded-xl bg-rose-600 px-4 py-2.5 text-sm font-semibold text-white shadow-md shadow-rose-600/10 transition hover:bg-rose-700 hover:shadow-rose-750/20 active:scale-95"
                >
                    Sí, eliminar
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    // --- Lógica del Modal de Eliminación ---
    const deleteModal = document.getElementById('delete-chat-modal');
    const deleteModalCard = document.getElementById('delete-modal-card');

    function toggleDeleteModal(show) {
        if (show) {
            deleteModal.classList.remove('hidden');
            setTimeout(() => {
                deleteModalCard.classList.remove('scale-95', 'opacity-0');
                deleteModalCard.classList.add('scale-100', 'opacity-100');
            }, 10);
        } else {
            deleteModalCard.classList.remove('scale-100', 'opacity-100');
            deleteModalCard.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                deleteModal.classList.add('hidden');
            }, 150);
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const messagesContainer = document.getElementById('messages-container');
        const chatForm = document.getElementById('chat-form');
        const messageInput = document.getElementById('message-input');
        const sendButton = document.getElementById('send-button');
        const noMessagesPrompt = document.getElementById('no-messages-prompt');

        const conversationId = @json($conversation->id);
        const currentUserId = @json(auth()->id());
        const currentUserName = @json(auth()->user()->name);

        // --- Desplazamiento automático al fondo ---
        function scrollToBottom() {
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }

        scrollToBottom();

        // --- Agregar mensaje al DOM con animación premium y chequeo de agrupaciones ---
        function appendMessage(body, formattedTime, isMe, senderName) {
            if (noMessagesPrompt) {
                noMessagesPrompt.remove();
            }

            const messageDiv = document.createElement('div');
            messageDiv.className = `flex flex-col ${isMe ? 'items-end' : 'items-start'} animate-fade-in-up`;

            messageDiv.innerHTML = `
                <div class="max-w-[72%] rounded-2xl px-4 py-2.5 shadow-sm text-sm leading-relaxed
                    ${isMe
                        ? 'bg-gradient-to-br from-emerald-600 to-emerald-700 text-white rounded-tr-none shadow-emerald-600/5'
                        : 'bg-white text-stone-850 border border-stone-200/80 rounded-tl-none'}"
                >
                    <p class="whitespace-pre-line">${escapeHtml(body)}</p>
                </div>
                <span class="mt-1 text-[9px] text-stone-400 font-medium px-1.5">
                    ${escapeHtml(formattedTime)}
                </span>
            `;

            messagesContainer.appendChild(messageDiv);
            scrollToBottom();
        }

        function escapeHtml(text) {
            const map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            };
            return text.replace(/[&<>"']/g, function(m) { return map[m]; });
        }

        // --- Envío AJAX del formulario ---
        chatForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const messageText = messageInput.value.trim();
            if (!messageText) return;

            messageInput.disabled = true;
            sendButton.disabled = true;

            const url = chatForm.action;
            const csrfToken = chatForm.querySelector('input[name="_token"]').value;

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    body: messageText
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error al enviar el mensaje');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    appendMessage(data.message.body, data.message.formatted_time, true, currentUserName);
                    messageInput.value = '';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('No se pudo enviar el mensaje. Inténtalo de nuevo.');
            })
            .finally(() => {
                messageInput.disabled = false;
                sendButton.disabled = false;
                messageInput.focus();
            });
        });

        // --- Laravel Echo: Escuchar en tiempo real a través de WebSockets ---
        if (window.Echo) {
            window.Echo.private(`conversations.${conversationId}`)
                .listen('MessageSent', (e) => {
                    if (parseInt(e.user_id) !== parseInt(currentUserId)) {
                        appendMessage(e.body, e.formatted_time, false, e.user_name);
                    }
                });
        }
    });
</script>
@endsection
