@extends('layouts.app')

@section('title', 'Chat con ' . $conversation->getOtherUser(auth()->user())->name . ' — ReClóset')

@section('content')
<style>
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(8px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .animate-fade-in-up {
        animation: fadeInUp 0.2s ease-out forwards;
    }
</style>

<div class="mx-auto max-w-4xl flex flex-col h-[calc(100vh-12rem)] min-h-[480px]">
    {{-- ===== Botón de regreso y barra de título ===== --}}
    <div class="mb-4 flex items-center justify-between">
        <a class="text-sm font-semibold text-stone-600 hover:text-stone-800 transition flex items-center gap-1.5" href="{{ route('chat.index') }}">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
            </svg>
            Volver a Mensajes
        </a>
        <span class="text-xs text-stone-400">ID de Chat: #{{ $conversation->id }}</span>
    </div>

    {{-- ===== Contenedor principal de Chat ===== --}}
    <div class="flex flex-1 flex-col overflow-hidden rounded-2xl border border-stone-200 bg-white shadow-sm">
        
        {{-- Cabecera: Info de la prenda y el otro usuario --}}
        @php
            $otherUser = $conversation->getOtherUser(auth()->user());
            $garment = $conversation->garment;
        @endphp
        <div class="border-b border-stone-150 bg-stone-50/50 p-4">
            <div class="flex flex-wrap items-center justify-between gap-4">
                {{-- Nombre de la persona --}}
                <div class="flex items-center gap-2.5">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-emerald-100 font-bold text-emerald-850">
                        {{ substr($otherUser->name, 0, 1) }}
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-stone-900">{{ $otherUser->name }}</h3>
                        <p class="text-xs text-stone-400">En línea / Conversación</p>
                    </div>
                </div>

                {{-- Mini Ficha de la Prenda --}}
                @if ($garment)
                    <a
                        href="{{ route('garments.show', $garment) }}"
                        class="flex items-center gap-3 rounded-xl border border-stone-200 bg-white p-2 text-left hover:border-stone-300 transition shrink-0 max-w-[320px]"
                    >
                        <div class="h-10 w-10 shrink-0 overflow-hidden rounded-lg bg-stone-100 flex items-center justify-center border border-stone-100">
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
                            <h4 class="text-xs font-semibold text-stone-900 truncate">{{ $garment->name }}</h4>
                            <p class="text-[10px] font-bold text-emerald-700 mt-0.5">
                                ${{ number_format((float) $garment->price, 2) }}
                                <span class="mx-1 text-stone-300 font-normal">|</span>
                                <span class="font-semibold px-1 rounded-sm bg-emerald-50 text-[9px]">
                                    {{ $garment->statusLabel() }}
                                </span>
                            </p>
                        </div>
                    </a>
                @else
                    <div class="text-xs italic text-stone-400 bg-stone-100 rounded-lg px-3 py-1.5">
                        Esta prenda ya no está disponible
                    </div>
                @endif
            </div>
        </div>

        {{-- Área de Mensajes --}}
        <div
            id="messages-container"
            class="flex-1 overflow-y-auto bg-stone-50 p-4 space-y-4 flex flex-col"
        >
            @if ($messages->isEmpty())
                <div id="no-messages-prompt" class="my-auto text-center p-8">
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-emerald-50 text-emerald-600">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 20.25c4.97 0 9-3.694 9-8.25s-4.03-8.25-9-8.25S3 7.444 3 12c0 2.104.859 4.023 2.273 5.48.432.447.74 1.04.586 1.641l-.318 1.235c-.027.106-.006.218.056.303A.358.358 0 0 0 5.897 21c.3 0 .591-.072.859-.21l1.75-.905c.148-.077.307-.104.468-.105 1.018.15 2.072.22 3.149.22H12Z" />
                        </svg>
                    </div>
                    <h4 class="mt-3 text-sm font-semibold text-stone-900">¡Inicia la conversación!</h4>
                    <p class="mt-1 text-xs text-stone-400 max-w-xs mx-auto">
                        Escribe un mensaje a continuación para resolver tus dudas sobre la prenda antes de comprar.
                    </p>
                </div>
            @else
                @foreach ($messages as $message)
                    @php
                        $isMe = (int) $message->user_id === (int) auth()->id();
                    @endphp
                    <div class="flex flex-col {{ $isMe ? 'items-end' : 'items-start' }}">
                        <div class="max-w-[75%] rounded-2xl px-4 py-2.5 shadow-sm text-sm
                            {{ $isMe
                                ? 'bg-emerald-600 text-white rounded-tr-none'
                                : 'bg-white text-stone-800 border border-stone-150 rounded-tl-none' }}"
                        >
                            <p class="leading-relaxed whitespace-pre-line">{{ $message->body }}</p>
                        </div>
                        <span class="mt-1 text-[10px] text-stone-400 px-1">
                            {{ $isMe ? 'Tú' : $message->user->name }} • {{ $message->created_at->format('H:i') }}
                        </span>
                    </div>
                @endforeach
            @endif
        </div>

        {{-- Formulario para Enviar Mensaje --}}
        <div class="border-t border-stone-200 bg-white p-4">
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
                    placeholder="Escribe tu mensaje aquí..."
                    required
                    autocomplete="off"
                    class="flex-1 rounded-xl border border-stone-200 bg-stone-50 px-4 py-3 text-sm text-stone-900 placeholder-stone-400 focus:border-emerald-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-emerald-500"
                >
                <button
                    type="submit"
                    id="send-button"
                    class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-emerald-600 text-white shadow-sm transition hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2"
                >
                    <svg class="h-5 w-5 rotate-90 transform" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" />
                    </svg>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
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

        // --- Agregar mensaje al DOM con animación premium ---
        function appendMessage(body, formattedTime, isMe, senderName) {
            // Eliminar el prompt de no mensajes si existe
            if (noMessagesPrompt) {
                noMessagesPrompt.remove();
            }

            const messageDiv = document.createElement('div');
            messageDiv.className = `flex flex-col ${isMe ? 'items-end' : 'items-start'} animate-fade-in-up`;

            messageDiv.innerHTML = `
                <div class="max-w-[75%] rounded-2xl px-4 py-2.5 shadow-sm text-sm
                    ${isMe
                        ? 'bg-emerald-600 text-white rounded-tr-none'
                        : 'bg-white text-stone-800 border border-stone-150 rounded-tl-none'}"
                >
                    <p class="leading-relaxed whitespace-pre-line">${escapeHtml(body)}</p>
                </div>
                <span class="mt-1 text-[10px] text-stone-400 px-1">
                    ${isMe ? 'Tú' : escapeHtml(senderName)} • ${escapeHtml(formattedTime)}
                </span>
            `;

            messagesContainer.appendChild(messageDiv);
            scrollToBottom();
        }

        // Helper para sanitizar entradas HTML
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

            // Deshabilitar temporalmente para evitar doble envío
            messageInput.disabled = true;
            sendButton.disabled = true;

            const url = chatForm.action;
            const csrfToken = chatForm.querySelector('input[name="_token"]').value;

            // Enviar vía Fetch
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
                    // Agregar instantáneamente
                    appendMessage(data.message.body, data.message.formatted_time, true, currentUserName);
                    messageInput.value = '';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('No se pudo enviar el mensaje. Inténtalo de nuevo.');
            })
            .finally(() => {
                // Rehabilitar
                messageInput.disabled = false;
                sendButton.disabled = false;
                messageInput.focus();
            });
        });

        // --- Laravel Echo: Escuchar en tiempo real a través de WebSockets ---
        if (window.Echo) {
            window.Echo.private(`conversations.${conversationId}`)
                .listen('MessageSent', (e) => {
                    // Evitar duplicar el mensaje si fue enviado por nosotros
                    if (parseInt(e.user_id) !== parseInt(currentUserId)) {
                        appendMessage(e.body, e.formatted_time, false, e.user_name);
                    }
                });
        } else {
            console.warn('Laravel Echo no está cargado. La actualización en tiempo real no estará disponible.');
        }
    });
</script>
@endsection
