@extends('layouts.app')

@section('title', 'Mis Mensajes — ReClóset')
@section('page_title', 'Mis Conversaciones')
@section('page_subtitle', 'Gestiona tus chats y negocia tus prendas en tiempo real.')

@section('content')
    <div class="w-full">
        {{-- ===== Contenedor principal de mensajes ===== --}}
        @if ($conversations->isEmpty())
            {{-- ===== Estado vacío ===== --}}
            <div class="mx-auto max-w-4xl rounded-3xl border border-stone-200/80 bg-white p-16 text-center shadow-xl shadow-stone-100/50 relative z-10 glass-card">
                <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-3xl bg-emerald-50 text-emerald-600 shadow-inner">
                    <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 0 1-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8Z" />
                    </svg>
                </div>
                <h3 class="mt-6 text-xl font-bold text-stone-900">Bandeja de entrada vacía</h3>
                <p class="mt-3 text-sm leading-relaxed text-stone-500 max-w-md mx-auto">
                    Explora el armario de ReClóset para interactuar con otros usuarios sobre sus prendas y negociar tus adquisiciones.
                </p>
                <div class="mt-8">
                    <a
                        href="{{ route('garments.explore') }}"
                        class="inline-flex items-center rounded-xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white shadow-md shadow-emerald-600/10 transition hover:bg-emerald-700 hover:shadow-emerald-750/20 active:scale-95 gap-2"
                    >
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                        </svg>
                        Explorar armario
                    </a>
                </div>
            </div>
        @else
            {{-- ===== Grid Dividido Responsivo ===== --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 h-[calc(100vh-14rem)] min-h-[550px] relative z-10">
                
                {{-- Columna Izquierda: Listado de conversaciones --}}
                <div class="md:col-span-1 flex flex-col h-full bg-white/80 backdrop-blur-md border border-stone-200/80 rounded-3xl shadow-xl shadow-stone-100/30 overflow-hidden">
                    <div class="p-4 border-b border-stone-100 bg-stone-50/50 flex justify-between items-center">
                        <span class="text-sm font-bold text-stone-850 inline-flex items-center gap-1.5">
                            <svg class="h-4.5 w-4.5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
                            </svg>
                            Bandeja de Entrada
                        </span>
                        <span class="text-xs bg-emerald-100 text-emerald-800 font-bold px-2 py-0.5 rounded-full">
                            {{ count($conversations) }}
                        </span>
                    </div>

                    <div class="flex-1 overflow-y-auto divide-y divide-stone-100">
                        @foreach ($conversations as $conversation)
                            @php
                                $otherUser = $conversation->getOtherUser(auth()->user());
                                $garment = $conversation->garment;
                                $lastMessage = $conversation->messages()->orderByDesc('created_at')->first();
                                $isUnread = $lastMessage && $lastMessage->user_id !== auth()->id() && !$lastMessage->read_at;
                            @endphp
                            <div
                                class="group block p-4 hover:bg-[#5aa9e6]/5 transition duration-200 relative {{ $isUnread ? 'bg-[#5aa9e6]/5' : '' }}"
                            >
                                <div class="flex items-center gap-3">
                                    {{-- Enlace envuelve a toda la información principal --}}
                                    <a
                                        href="{{ route('chat.show', $conversation) }}"
                                        class="flex flex-1 items-center gap-3 min-w-0"
                                    >
                                        {{-- Imagen miniatura de la prenda con sombra --}}
                                        <div class="h-12 w-12 shrink-0 overflow-hidden rounded-2xl border border-stone-200 bg-stone-50 flex items-center justify-center shadow-sm">
                                            @if ($garment && $garment->image_path)
                                                <img
                                                    src="{{ asset('storage/' . $garment->image_path) }}"
                                                    alt="{{ $garment->name }}"
                                                    class="h-full w-full object-cover transition duration-300 group-hover:scale-105"
                                                >
                                            @else
                                                <svg class="h-6 w-6 text-stone-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0 0 22.5 18.75V5.25A2.25 2.25 0 0 0 20.25 3H3.75A2.25 2.25 0 0 0 1.5 5.25v13.5A2.25 2.25 0 0 0 3.75 21Z" />
                                                </svg>
                                            @endif
                                        </div>

                                        {{-- Detalles del chat --}}
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-baseline justify-between gap-1">
                                                <h4 class="text-xs font-bold text-stone-900 group-hover:text-emerald-700 transition truncate">
                                                    {{ $otherUser->name }}
                                                </h4>
                                                <span class="text-[10px] text-stone-400 shrink-0 font-medium">
                                                    {{ $conversation->last_message_at ? $conversation->last_message_at->diffForHumans(null, true) : $conversation->created_at->diffForHumans(null, true) }}
                                                </span>
                                            </div>
                                            
                                            <div class="flex items-center gap-1 mt-0.5 text-[10px] font-bold text-emerald-800 truncate">
                                                <span>{{ $garment ? $garment->name : 'Prenda eliminada' }}</span>
                                                @if($garment)
                                                    <span class="h-0.5 w-0.5 rounded-full bg-stone-300 shrink-0"></span>
                                                    <span class="bg-emerald-50 px-1 py-0.2 rounded text-emerald-700 font-bold">${{ number_format((float) $garment->price, 2) }}</span>
                                                @endif
                                            </div>
                                            
                                            <p class="mt-1 text-xs text-stone-500 truncate leading-relaxed {{ $isUnread ? 'font-bold text-stone-800' : '' }}">
                                                @if ($lastMessage)
                                                    @if ($lastMessage->user_id === auth()->id())
                                                        <span class="text-stone-400 font-normal">Tú:</span>
                                                    @endif
                                                    {{ $lastMessage->body }}
                                                @else
                                                    <span class="italic text-stone-400/90 font-normal">Conversación iniciada.</span>
                                                @endif
                                            </p>
                                        </div>
                                    </a>

                                    {{-- Botón de eliminación y estado --}}
                                    <div class="flex items-center gap-1 shrink-0">
                                        @if ($isUnread)
                                            <span class="relative flex h-2 w-2 mr-1">
                                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                                <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-600"></span>
                                            </span>
                                        @endif

                                        <button
                                            type="button"
                                            onclick="confirmDeleteChat('{{ route('chat.destroy', $conversation) }}', '{{ addslashes($otherUser->name) }}', '{{ $garment ? addslashes($garment->name) : 'Prenda eliminada' }}')"
                                            class="md:opacity-0 group-hover:opacity-100 inline-flex h-8 w-8 items-center justify-center rounded-xl text-stone-400 hover:bg-rose-50 hover:text-rose-600 transition duration-200 cursor-pointer"
                                            title="Eliminar conversación"
                                        >
                                            <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                            </svg>
                                        </button>

                                        <a href="{{ route('chat.show', $conversation) }}" class="inline-flex h-8 w-8 items-center justify-center rounded-xl hover:bg-stone-100 text-stone-400 transition">
                                            <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Columna Derecha: Vista previa / Selecciona conversación --}}
                <div class="hidden md:flex md:col-span-2 flex-col items-center justify-center h-full bg-white/70 backdrop-blur-md border border-stone-200/80 rounded-3xl shadow-xl shadow-stone-100/30 p-8 text-center relative overflow-hidden">
                    <div class="absolute -top-12 -right-12 w-48 h-48 rounded-full bg-[#7fc8f8]/10 blur-2xl pointer-events-none"></div>
                    
                    <div class="mx-auto flex h-24 w-24 items-center justify-center rounded-3xl bg-[#5aa9e6]/10 text-[#2974a6] shadow-sm mb-6 border border-[#5aa9e6]/20">
                        <svg class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.3.025-.603.048-.908.069m.078-8.645c-.139-.893-.787-1.602-1.657-1.847-1.129-.317-2.31-.482-3.535-.482s-2.406.165-3.535.482c-.87.245-1.517.954-1.657 1.847m10.384 0a15.82 15.82 0 1 1-20.768 0m20.768 0v8.645m-20.768-8.645v8.645m0 0a1.21 1.21 0 0 1-.078-.069m0 0C.847 16.993 0 16.03 0 14.894V10.61c0-.969.616-1.813 1.5-2.097M4.5 9v3.75M19.5 9v3.75m-15 3h15M12 3v18" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-stone-900">Comienza a negociar</h3>
                    <p class="mt-2 text-sm text-stone-500 max-w-sm">
                        Selecciona uno de tus chats de la izquierda para ver el historial de mensajes, conversar con el vendedor o coordinar la entrega.
                    </p>
                </div>
            </div>
        @endif
    </div>

    {{-- ===== Modal de confirmación de eliminación ===== --}}
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
                        ¿Estás seguro de que deseas eliminar la conversación con <strong id="delete-chat-user" class="text-stone-850"></strong> sobre la prenda <strong id="delete-chat-garment" class="text-stone-850"></strong>?
                    </p>
                    <p class="mt-1 text-xs text-rose-500 font-semibold">
                        Esta acción es permanente y eliminará todo el historial de mensajes de ambos usuarios.
                    </p>
                </div>
            </div>
            <div class="mt-2 flex items-center justify-end gap-3">
                <button
                    type="button"
                    class="rounded-xl px-4 py-2.5 text-sm font-semibold text-stone-600 hover:bg-stone-50 hover:text-stone-800 transition active:scale-95 cursor-pointer"
                    onclick="toggleDeleteModal(false)"
                >
                    Cancelar
                </button>
                <form id="delete-chat-form" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button
                        type="submit"
                        class="inline-flex items-center rounded-xl bg-rose-600 px-4 py-2.5 text-sm font-semibold text-white shadow-md shadow-rose-600/10 transition hover:bg-rose-700 hover:shadow-rose-750/20 active:scale-95 cursor-pointer gap-1.5"
                    >
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                        </svg>
                        Sí, eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
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

        function confirmDeleteChat(actionUrl, otherUserName, garmentName) {
            document.getElementById('delete-chat-form').action = actionUrl;
            document.getElementById('delete-chat-user').innerText = otherUserName;
            document.getElementById('delete-chat-garment').innerText = garmentName;
            toggleDeleteModal(true);
        }
    </script>
@endsection
