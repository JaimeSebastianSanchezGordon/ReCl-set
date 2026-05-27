@extends('layouts.app')

@section('title', 'Mis Mensajes — ReClóset')
@section('page_title', 'Bandeja de Entrada')
@section('page_subtitle', 'Conversaciones activas sobre prendas de vestir en venta o intercambio.')

@section('content')
    <div class="mx-auto max-w-4xl">
        @if ($conversations->isEmpty())
            {{-- ===== Estado vacío ===== --}}
            <div class="rounded-2xl border border-stone-200 bg-white p-12 text-center shadow-sm">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-emerald-50 text-emerald-600">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 0 1-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8Z" />
                    </svg>
                </div>
                <h3 class="mt-4 text-lg font-semibold text-stone-900">No tienes mensajes</h3>
                <p class="mt-2 text-sm leading-relaxed text-stone-500 max-w-md mx-auto">
                    Cuando explores prendas y chatees con otros usuarios sobre ellas para ver si las compras o no, tus conversaciones aparecerán aquí.
                </p>
                <div class="mt-6">
                    <a
                        href="{{ route('garments.explore') }}"
                        class="inline-flex items-center rounded-md bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700"
                    >
                        Explorar prendas
                    </a>
                </div>
            </div>
        @else
            {{-- ===== Listado de conversaciones ===== --}}
            <div class="overflow-hidden rounded-2xl border border-stone-200 bg-white shadow-sm">
                <div class="divide-y divide-stone-100">
                    @foreach ($conversations as $conversation)
                        @php
                            $otherUser = $conversation->getOtherUser(auth()->user());
                            $garment = $conversation->garment;
                            $lastMessage = $conversation->messages()->orderByDesc('created_at')->first();
                            $isUnread = $lastMessage && $lastMessage->user_id !== auth()->id() && !$lastMessage->read_at;
                        @endphp
                        <a
                            href="{{ route('chat.show', $conversation) }}"
                            class="block p-5 hover:bg-stone-50/80 transition duration-150 relative {{ $isUnread ? 'bg-emerald-50/20' : '' }}"
                        >
                            <div class="flex items-center gap-4">
                                {{-- Imagen miniatura de la prenda --}}
                                <div class="h-14 w-14 shrink-0 overflow-hidden rounded-xl border border-stone-200 bg-stone-100 flex items-center justify-center">
                                    @if ($garment && $garment->image_path)
                                        <img
                                            src="{{ asset('storage/' . $garment->image_path) }}"
                                            alt="{{ $garment->name }}"
                                            class="h-full w-full object-cover"
                                        >
                                    @else
                                        <svg class="h-6 w-6 text-stone-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0 0 22.5 18.75V5.25A2.25 2.25 0 0 0 20.25 3H3.75A2.25 2.25 0 0 0 1.5 5.25v13.5A2.25 2.25 0 0 0 3.75 21Z" />
                                        </svg>
                                    @endif
                                </div>

                                {{-- Información principal --}}
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between gap-2">
                                        <h4 class="text-sm font-semibold text-stone-900 truncate">
                                            {{ $otherUser->name }}
                                        </h4>
                                        <span class="text-xs text-stone-400 shrink-0">
                                            {{ $conversation->last_message_at ? $conversation->last_message_at->diffForHumans() : $conversation->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                    <p class="mt-0.5 text-xs font-medium text-emerald-700">
                                        Prenda: {{ $garment ? $garment->name : 'Prenda eliminada' }} 
                                        @if($garment)
                                            <span class="text-stone-400">•</span> ${{ number_format((float) $garment->price, 2) }}
                                        @endif
                                    </p>
                                    <p class="mt-1 text-sm text-stone-500 truncate {{ $isUnread ? 'font-medium text-stone-800' : '' }}">
                                        @if ($lastMessage)
                                            @if ($lastMessage->user_id === auth()->id())
                                                <span class="text-stone-400 font-normal">Tú:</span>
                                            @endif
                                            {{ $lastMessage->body }}
                                        @else
                                            <span class="italic text-stone-400">Conversación iniciada. Envíale un mensaje para comenzar.</span>
                                        @endif
                                    </p>
                                </div>

                                {{-- Indicadores premium --}}
                                <div class="flex shrink-0 items-center justify-center gap-2">
                                    @if ($isUnread)
                                        <span class="h-2.5 w-2.5 rounded-full bg-emerald-600 ring-4 ring-emerald-50"></span>
                                    @endif
                                    <svg class="h-5 w-5 text-stone-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                                    </svg>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endsection
