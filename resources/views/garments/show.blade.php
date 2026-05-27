@extends('layouts.app')

@section('title', $garment->name . ' — ReClóset')
@section('page_title', 'Detalle de prenda')

@section('actions')
    <a class="text-xs font-bold text-stone-600 hover:text-stone-850 transition inline-flex items-center gap-1.5 cursor-pointer uppercase tracking-wider" href="{{ route('garments.explore') }}">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
        </svg>
        Explorar
    </a>
    @if ($isOwner)
        <a
            class="inline-flex items-center rounded-xl border border-[#5aa9e6]/30 bg-white px-4 py-2.5 text-xs font-bold text-[#2974a6] transition hover:bg-[#5aa9e6]/5 gap-1.5 shadow-sm"
            href="{{ route('garments.edit', $garment) }}"
        >
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
            </svg>
            Editar
        </a>
    @endif
@endsection

@section('content')
    <section class="rounded-3xl border border-stone-200/80 bg-white/70 backdrop-blur-sm shadow-lg overflow-hidden relative z-10">

        {{-- ===== Imagen + Info principal ===== --}}
        <div class="flex flex-col md:flex-row">

            {{-- Imagen --}}
            <div class="md:w-2/5 bg-stone-50 flex items-center justify-center min-h-[320px] border-r border-stone-200">
                @if ($garment->image_path)
                    <img
                        src="{{ asset('storage/' . $garment->image_path) }}"
                        alt="{{ $garment->name }}"
                        class="h-full w-full object-cover"
                    >
                @else
                    <div class="flex flex-col items-center justify-center text-stone-300 p-12">
                        <svg class="h-20 w-20 mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0 0 22.5 18.75V5.25A2.25 2.25 0 0 0 20.25 3H3.75A2.25 2.25 0 0 0 1.5 5.25v13.5A2.25 2.25 0 0 0 3.75 21Z" />
                        </svg>
                        <span class="text-xs font-bold uppercase tracking-wider text-stone-400">Sin imagen disponible</span>
                    </div>
                @endif
            </div>

            {{-- Datos principales --}}
            <div class="flex-1 p-8 flex flex-col justify-between">
                <div>
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h2 class="text-2xl font-black text-stone-900 tracking-tight leading-snug">{{ $garment->name }}</h2>
                            <p class="mt-1 text-sm text-stone-400 font-bold uppercase tracking-wider">{{ $garment->categoryLabel() }}</p>
                        </div>
                        <div class="flex flex-col items-end gap-2 shrink-0">
                            <span class="rounded-2xl bg-emerald-50 border border-emerald-100 px-4 py-2 text-xl font-black text-emerald-800 shadow-sm">
                                ${{ number_format((float) $garment->price, 2) }}
                            </span>
                            @php
                                $statusColors = [
                                    'available' => 'bg-emerald-50 text-emerald-800 border-emerald-250',
                                    'reserved'  => 'bg-amber-50 text-amber-800 border-amber-300',
                                    'sold'      => 'bg-rose-50 text-rose-800 border-rose-200',
                                ];
                                $statusClass = $statusColors[$garment->status] ?? 'bg-stone-55 text-stone-800 border-stone-200';
                            @endphp
                            <span class="rounded-full border px-3.5 py-0.5 text-[10px] font-black uppercase tracking-wider {{ $statusClass }}">
                                {{ $garment->statusLabel() }}
                            </span>
                        </div>
                    </div>

                    <div class="mt-6 border-t border-stone-100 pt-6">
                        <h4 class="text-xs font-bold text-stone-400 uppercase tracking-widest">Descripción</h4>
                        <p class="mt-2 text-sm leading-relaxed text-stone-600 font-medium">
                            {{ $garment->description ?? 'Sin descripción disponible.' }}
                        </p>
                    </div>

                    {{-- Atributos --}}
                    <div class="mt-6 grid gap-4 sm:grid-cols-2">
                        <div class="rounded-2xl border border-stone-200/60 bg-stone-50/50 p-4">
                            <p class="text-[10px] uppercase font-black tracking-wider text-stone-400">Talla</p>
                            <p class="mt-1 text-sm font-extrabold text-stone-800">{{ $garment->sizeLabel() }}</p>
                        </div>
                        <div class="rounded-2xl border border-stone-200/60 bg-stone-50/50 p-4">
                            <p class="text-[10px] uppercase font-black tracking-wider text-stone-400">Color</p>
                            <p class="mt-1 text-sm font-extrabold text-stone-800">{{ $garment->colorLabel() }}</p>
                        </div>
                    </div>
                </div>

                {{-- Acciones del propietario --}}
                @if ($isOwner)
                    <div class="mt-8 flex flex-wrap items-center gap-3 border-t border-stone-150 pt-6">
                        <a
                            class="inline-flex items-center rounded-xl bg-[#5aa9e6] px-4 py-2.5 text-xs font-bold text-white shadow-md transition hover:bg-[#3a8fcb] gap-1.5 cursor-pointer uppercase tracking-wider"
                            href="{{ route('garments.edit', $garment) }}"
                        >
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                            </svg>
                            Editar prenda
                        </a>

                        {{-- Cambio rápido de estado --}}
                        @if ($garment->status === 'available')
                            <form method="POST" action="{{ route('garments.updateStatus', $garment) }}">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="reserved">
                                <button
                                    type="submit"
                                    class="inline-flex items-center rounded-xl border border-[#ffe45e]/50 bg-white px-4 py-2.5 text-xs font-bold text-[#b39500] transition hover:bg-[#ffe45e]/10 gap-1.5 cursor-pointer uppercase tracking-wider"
                                >
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0V10.5m-3 1.125c0-.621.504-1.125 1.125-1.125h12.75c.621 0 1.125.504 1.125 1.125v9a1.125 1.125 0 0 1-1.125 1.125H5.625a1.125 1.125 0 0 1-1.125-1.125v-9Z" />
                                    </svg>
                                    Marcar como Reservada
                                </button>
                            </form>
                        @elseif ($garment->status === 'reserved')
                            <form method="POST" action="{{ route('garments.updateStatus', $garment) }}">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="sold">
                                <button
                                    type="submit"
                                    class="inline-flex items-center rounded-xl border border-rose-200 bg-white px-4 py-2.5 text-xs font-bold text-rose-700 transition hover:bg-rose-50 gap-1.5 cursor-pointer uppercase tracking-wider"
                                >
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z" />
                                    </svg>
                                    Marcar como Vendida
                                </button>
                            </form>
                            <form method="POST" action="{{ route('garments.updateStatus', $garment) }}">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="available">
                                <button
                                    type="submit"
                                    class="inline-flex items-center rounded-xl border border-emerald-200 bg-white px-4 py-2.5 text-xs font-bold text-emerald-700 transition hover:bg-emerald-50 gap-1.5 cursor-pointer uppercase tracking-wider"
                                >
                                    Regresar a Disponible
                                </button>
                            </form>
                        @endif

                        {{-- Botón que abre el modal de confirmación --}}
                        <button
                            type="button"
                            class="ml-auto text-xs font-bold text-rose-600 hover:text-rose-700 transition cursor-pointer inline-flex items-center gap-1.5 uppercase tracking-wider"
                            onclick="document.getElementById('modal-eliminar-{{ $garment->id }}').classList.remove('hidden')"
                        >
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                            </svg>
                            Eliminar
                        </button>
                    </div>
                @else
                    <div class="mt-8 flex flex-wrap items-center gap-3 border-t border-stone-150 pt-6">
                        <a
                            class="inline-flex items-center rounded-2xl bg-emerald-600 px-6 py-4 text-sm font-bold text-white shadow-md transition hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 gap-2 cursor-pointer uppercase tracking-wider"
                            href="{{ route('chat.start', $garment) }}"
                        >
                            <svg class="h-5.5 w-5.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 9.75a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 0 1-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8Z" />
                            </svg>
                            Chatear con el vendedor
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </section>

    {{-- ===== Modal de confirmación de eliminación ===== --}}
    @if ($isOwner)
        <div
            id="modal-eliminar-{{ $garment->id }}"
            class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm"
            onclick="if(event.target===this) this.classList.add('hidden')"
        >
            <div class="mx-4 w-full max-w-md rounded-3xl border border-stone-200 bg-white p-6 shadow-2xl flex flex-col gap-4">
                <div class="flex items-start gap-4">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-rose-50 text-rose-600 shadow-inner">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-stone-900">¿Eliminar esta prenda?</h3>
                        <p class="mt-2 text-sm text-stone-600 leading-relaxed">
                            ¿Estás seguro de que deseas eliminar permanentemente la prenda <strong>{{ $garment->name }}</strong>?
                            Esta acción no se puede deshacer y se eliminará toda la información adjunta.
                        </p>
                    </div>
                </div>
                <div class="mt-4 flex items-center justify-end gap-3">
                    <button
                        type="button"
                        class="rounded-xl px-4 py-2.5 text-sm font-semibold text-stone-600 hover:bg-stone-50 hover:text-stone-850 transition active:scale-95 cursor-pointer"
                        onclick="document.getElementById('modal-eliminar-{{ $garment->id }}').classList.add('hidden')"
                    >
                        Cancelar
                    </button>
                    <form method="POST" action="{{ route('garments.destroy', $garment) }}">
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
    @endif
@endsection
