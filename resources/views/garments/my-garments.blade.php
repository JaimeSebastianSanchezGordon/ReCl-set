{{--
    Vista "Mis Prendas" — Panel privado del usuario.
    Muestra SOLO las prendas del usuario actual, con todos los estados.
    Incluye botones de Editar, Eliminar (con modal) y cambio de estado.
--}}

@extends('layouts.app')

@section('title', 'Mis Prendas — ReClóset')

@section('content')
    <div class="grid gap-6">

        {{-- Header row with Title and Inline Filters --}}
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 relative z-20 mt-2">
            <h1 class="text-3xl font-extrabold text-stone-900 tracking-tight shrink-0">Mis prendas</h1>

            <form class="flex flex-col sm:flex-row sm:items-center gap-2 w-full lg:w-auto" method="GET" action="{{ route('garments.my') }}">
                <select class="w-full sm:w-auto rounded-full border border-stone-200 bg-white px-3.5 py-2 text-xs font-bold text-stone-750 shadow-sm focus:outline-none focus:ring-2 focus:ring-[#5aa9e6] cursor-pointer" name="category">
                    <option value="">Todas las categorías</option>
                    @foreach (\App\Models\Garment::CATEGORIES as $key => $label)
                        <option value="{{ $key }}" @selected(($filters['category'] ?? '') === $key)>{{ $label }}</option>
                    @endforeach
                </select>

                <select class="w-full sm:w-auto rounded-full border border-stone-200 bg-white px-3.5 py-2 text-xs font-bold text-stone-750 shadow-sm focus:outline-none focus:ring-2 focus:ring-[#5aa9e6] cursor-pointer" name="size">
                    <option value="">Todas las tallas</option>
                    @foreach (\App\Models\Garment::SIZES as $key => $label)
                        <option value="{{ $key }}" @selected(($filters['size'] ?? '') === $key)>{{ $label }}</option>
                    @endforeach
                </select>

                <select class="w-full sm:w-auto rounded-full border border-stone-200 bg-white px-3.5 py-2 text-xs font-bold text-stone-750 shadow-sm focus:outline-none focus:ring-2 focus:ring-[#5aa9e6] cursor-pointer" name="color">
                    <option value="">Todos los colores</option>
                    @foreach (\App\Models\Garment::COLORS as $key => $label)
                        <option value="{{ $key }}" @selected(($filters['color'] ?? '') === $key)>{{ $label }}</option>
                    @endforeach
                </select>

                <select class="w-full sm:w-auto rounded-full border border-stone-200 bg-white px-3.5 py-2 text-xs font-bold text-stone-750 shadow-sm focus:outline-none focus:ring-2 focus:ring-[#5aa9e6] cursor-pointer" name="status">
                    <option value="">Todos los estados</option>
                    @foreach (\App\Models\Garment::STATUSES as $key => $label)
                        <option value="{{ $key }}" @selected(($filters['status'] ?? '') === $key)>{{ $label }}</option>
                    @endforeach
                </select>

                <div class="flex items-center gap-2 w-full sm:w-auto justify-end sm:justify-start">
                    <button
                        class="inline-flex items-center justify-center rounded-full bg-stone-900 px-4 py-2 text-xs font-bold text-white shadow-md transition hover:bg-stone-800 gap-1.5 cursor-pointer uppercase tracking-wider"
                        type="submit"
                    >
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.602 10.602Z" />
                        </svg>
                        Filtrar
                    </button>

                    @if(!empty($filters['category']) || !empty($filters['size']) || !empty($filters['color']) || !empty($filters['status']))
                        <a class="text-xs font-bold text-stone-600 hover:text-stone-850 transition inline-flex items-center gap-1 uppercase tracking-wider cursor-pointer px-2 py-1.5" href="{{ route('garments.my') }}">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                            </svg>
                            Limpiar
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- ===== Métricas del usuario ===== --}}
        <section class="grid gap-4 sm:grid-cols-4 relative z-10">
            <div class="rounded-3xl border border-stone-200/80 bg-white/70 backdrop-blur-sm p-5 shadow-md flex items-center gap-4">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-stone-100 text-stone-700 border border-stone-200">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 13.5h3.86a2.25 2.25 0 0 1 2.008 1.24l.885 1.77a2.25 2.25 0 0 0 2.007 1.24h1.98a2.25 2.25 0 0 0 2.007-1.24l.885-1.77a2.25 2.25 0 0 1 2.007-1.24h3.86m-18 0h18a2.25 2.25 0 0 1 2.25 2.25v4.25a2.25 2.25 0 0 1-2.25 2.25H2.25A2.25 2.25 0 0 1 0 18.5v-4.25A2.25 2.25 0 0 1 2.25 13.5Z" />
                    </svg>
                </div>
                <div>
                    <p class="text-[10px] uppercase font-black tracking-wider text-stone-500">Total publicados</p>
                    <p class="mt-1 text-2xl font-black text-stone-900 leading-none">{{ $metrics['total'] }}</p>
                </div>
            </div>
            <div class="rounded-3xl border border-[#5aa9e6]/25 bg-[#5aa9e6]/10 backdrop-blur-sm p-5 shadow-md flex items-center gap-4">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-[#5aa9e6]/20 text-[#2974a6] border border-[#5aa9e6]/30">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z" />
                    </svg>
                </div>
                <div>
                    <p class="text-[10px] uppercase font-black tracking-wider text-[#2974a6]">Disponibles</p>
                    <p class="mt-1 text-2xl font-black text-[#1d577e] leading-none">{{ $metrics['available'] }}</p>
                </div>
            </div>
            <div class="rounded-3xl border border-[#ffe45e]/40 bg-[#ffe45e]/15 backdrop-blur-sm p-5 shadow-md flex items-center gap-4">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-[#ffe45e]/35 text-[#b39500] border border-[#ffe45e]/50">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </div>
                <div>
                    <p class="text-[10px] uppercase font-black tracking-wider text-[#b39500]">Reservadas</p>
                    <p class="mt-1 text-2xl font-black text-[#8c7900] leading-none">{{ $metrics['reserved'] }}</p>
                </div>
            </div>
            <div class="rounded-3xl border border-rose-200 bg-rose-50/70 backdrop-blur-sm p-5 shadow-md flex items-center gap-4">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-rose-100 text-rose-700 border border-rose-200">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 1 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                    </svg>
                </div>
                <div>
                    <p class="text-[10px] uppercase font-black tracking-wider text-rose-700">Vendidas</p>
                    <p class="mt-1 text-2xl font-black text-rose-900 leading-none">{{ $metrics['sold'] }}</p>
                </div>
            </div>
        </section>

        {{-- ===== Lista de prendas del usuario ===== --}}
        <section class="grid gap-6 sm:grid-cols-2 relative z-10">
            @forelse ($garments as $garment)
                @php
                    $statusColors = [
                        'available' => 'bg-emerald-50 text-emerald-800 border-emerald-250',
                        'reserved'  => 'bg-amber-50 text-amber-800 border-amber-300',
                        'sold'      => 'bg-rose-50 text-rose-800 border-rose-200',
                    ];
                    $statusClass = $statusColors[$garment->status] ?? 'bg-stone-55 text-stone-800 border-stone-200';
                @endphp

                <article class="rounded-3xl border border-stone-200 bg-white/80 backdrop-blur-sm shadow-md overflow-hidden transition duration-300 hover:shadow-xl flex">
                    {{-- Miniatura --}}
                    <div class="w-32 shrink-0 bg-stone-50 flex items-center justify-center border-r border-stone-150 relative">
                        @if ($garment->image_path)
                            <img
                                src="{{ asset('storage/' . $garment->image_path) }}"
                                alt="{{ $garment->name }}"
                                class="h-full w-full object-cover"
                            >
                        @else
                            <svg class="h-10 w-10 text-stone-300" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0 0 22.5 18.75V5.25A2.25 2.25 0 0 0 20.25 3H3.75A2.25 2.25 0 0 0 1.5 5.25v13.5A2.25 2.25 0 0 0 3.75 21Z" />
                            </svg>
                        @endif
                    </div>

                    {{-- Info --}}
                    <div class="flex-1 p-5 flex flex-col justify-between">
                        <div>
                            <div class="flex items-start justify-between gap-2">
                                <div class="min-w-0">
                                    <h2 class="font-bold text-stone-900 leading-snug truncate">{{ $garment->name }}</h2>
                                    <p class="text-xs text-stone-400 font-medium mt-0.5">{{ $garment->categoryLabel() }}</p>
                                </div>
                                <span class="rounded-2xl border px-3 py-0.5 text-[10px] font-black uppercase tracking-wider {{ $statusClass }}">
                                    {{ $garment->statusLabel() }}
                                </span>
                            </div>

                            <div class="mt-3 flex flex-wrap items-center gap-2 text-[10px] font-bold text-stone-600">
                                <span class="rounded-full bg-stone-100 px-2.5 py-0.5 border border-stone-200/50 uppercase tracking-wider">{{ $garment->sizeLabel() }}</span>
                                <span class="rounded-full bg-stone-100 px-2.5 py-0.5 border border-stone-200/50 uppercase tracking-wider">{{ $garment->colorLabel() }}</span>
                                <span class="rounded-full bg-emerald-50 px-2.5 py-0.5 border border-emerald-100 font-black text-emerald-800">
                                    ${{ number_format((float) $garment->price, 2) }}
                                </span>
                            </div>
                        </div>

                        {{-- Acciones del propietario --}}
                        <div class="mt-4 flex flex-wrap items-center gap-2 pt-3 border-t border-stone-100">
                            <a
                                class="inline-flex items-center rounded-xl border border-stone-200 bg-white px-3 py-1.5 text-xs font-bold text-stone-700 transition hover:bg-stone-50 hover:border-stone-300 gap-1"
                                href="{{ route('garments.show', $garment) }}"
                            >
                                Ver
                            </a>
                            <a
                                class="inline-flex items-center rounded-xl border border-[#5aa9e6]/30 bg-white px-3 py-1.5 text-xs font-bold text-[#2974a6] transition hover:bg-[#5aa9e6]/5 hover:border-[#5aa9e6]/50 gap-1"
                                href="{{ route('garments.edit', $garment) }}"
                            >
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                                </svg>
                                Editar
                            </a>

                            {{-- Cambio rápido de estado --}}
                            @if ($garment->status === 'available')
                                <form method="POST" action="{{ route('garments.updateStatus', $garment) }}" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="reserved">
                                    <button type="submit" class="inline-flex items-center rounded-xl border border-[#ffe45e]/50 bg-white px-3 py-1.5 text-xs font-bold text-[#b39500] transition hover:bg-[#ffe45e]/10 cursor-pointer gap-1">
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0V10.5m-3 1.125c0-.621.504-1.125 1.125-1.125h12.75c.621 0 1.125.504 1.125 1.125v9a1.125 1.125 0 0 1-1.125 1.125H5.625a1.125 1.125 0 0 1-1.125-1.125v-9Z" />
                                        </svg>
                                        Reservar
                                    </button>
                                </form>
                            @elseif ($garment->status === 'reserved')
                                <form method="POST" action="{{ route('garments.updateStatus', $garment) }}" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="sold">
                                    <button type="submit" class="inline-flex items-center rounded-xl border border-rose-200 bg-white px-3 py-1.5 text-xs font-bold text-rose-700 transition hover:bg-rose-50 cursor-pointer gap-1">
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z" />
                                        </svg>
                                        Vendida
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('garments.updateStatus', $garment) }}" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="available">
                                    <button type="submit" class="inline-flex items-center rounded-xl border border-emerald-200 bg-white px-3 py-1.5 text-xs font-bold text-emerald-700 transition hover:bg-emerald-50 cursor-pointer gap-1">
                                        Disponible
                                    </button>
                                </form>
                            @endif

                            {{-- Eliminar — abre modal de confirmación --}}
                            <button
                                type="button"
                                class="ml-auto text-xs font-bold text-rose-600 hover:text-rose-700 transition cursor-pointer inline-flex items-center gap-1"
                                onclick="document.getElementById('modal-eliminar-{{ $garment->id }}').classList.remove('hidden')"
                            >
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                </svg>
                                Eliminar
                            </button>
                        </div>
                    </div>
                </article>
            @empty
                <div class="sm:col-span-2 rounded-3xl border border-dashed border-stone-300 bg-white/70 backdrop-blur-md p-16 text-center shadow-lg relative z-10 glass-card">
                    <svg class="mx-auto h-16 w-16 text-stone-300 mb-4" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                    </svg>
                    <h3 class="text-lg font-bold text-stone-900">Aún no tienes prendas publicadas</h3>
                    <p class="text-sm text-stone-500 max-w-sm mx-auto mt-2">Gestiona e intercambia tus propias prendas cargándolas hoy mismo en ReClóset.</p>
                    <a
                        href="{{ route('garments.create') }}"
                        class="mt-6 inline-flex items-center rounded-xl bg-emerald-600 px-5 py-3 text-xs font-bold text-white shadow-md transition hover:bg-emerald-700 gap-1.5 uppercase tracking-wider"
                    >
                        <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        ¡Publica tu primera prenda!
                    </a>
                </div>
            @endforelse
        </section>

        {{-- ===== Paginación ===== --}}
        @if ($garments->hasPages())
            <div class="mt-4 relative z-10">
                {{ $garments->links() }}
            </div>
        @endif
    </div>

    {{-- ===== Modales de confirmación de eliminación ===== --}}
    @foreach ($garments as $garment)
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
                            ¿Estás seguro de que deseas eliminar <strong>{{ $garment->name }}</strong>?
                            Esta acción es permanente e irreversible. Se eliminará también la imagen y todo el historial relacionado.
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
    @endforeach
@endsection
