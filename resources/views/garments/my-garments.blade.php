{{--
    Vista "Mis Prendas" — Panel privado del usuario.
    Muestra SOLO las prendas del usuario actual, con todos los estados.
    Incluye botones de Editar, Eliminar (con modal) y cambio de estado.
--}}

@extends('layouts.app')

@section('title', 'Mis Prendas — ReClóset')
@section('page_title', 'Mis Prendas')
@section('page_subtitle', 'Gestiona tu inventario de prendas publicadas.')

@section('actions')
    <a
        class="inline-flex items-center rounded-md bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700"
        href="{{ route('garments.create') }}"
    >
        + Publicar prenda
    </a>
@endsection

@section('content')
    <div class="grid gap-6">

        {{-- ===== Métricas del usuario ===== --}}
        <section class="grid gap-4 sm:grid-cols-4">
            <div class="rounded-2xl border border-stone-200 bg-white p-4 shadow-sm">
                <p class="text-xs uppercase tracking-wider text-stone-500">Total</p>
                <p class="mt-2 text-2xl font-semibold text-stone-900">{{ $metrics['total'] }}</p>
            </div>
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 shadow-sm">
                <p class="text-xs uppercase tracking-wider text-emerald-700">Disponibles</p>
                <p class="mt-2 text-2xl font-semibold text-emerald-900">{{ $metrics['available'] }}</p>
            </div>
            <div class="rounded-2xl border border-amber-200 bg-amber-50 p-4 shadow-sm">
                <p class="text-xs uppercase tracking-wider text-amber-700">Reservadas</p>
                <p class="mt-2 text-2xl font-semibold text-amber-900">{{ $metrics['reserved'] }}</p>
            </div>
            <div class="rounded-2xl border border-rose-200 bg-rose-50 p-4 shadow-sm">
                <p class="text-xs uppercase tracking-wider text-rose-700">Vendidas</p>
                <p class="mt-2 text-2xl font-semibold text-rose-900">{{ $metrics['sold'] }}</p>
            </div>
        </section>

        {{-- ===== Filtros ===== --}}
        <section class="rounded-2xl border border-stone-200 bg-white p-5 shadow-sm">
            <form class="grid gap-4 sm:grid-cols-5" method="GET" action="{{ route('garments.my') }}">

                <select class="rounded-md border border-stone-300 bg-white px-3 py-2 text-sm" name="category">
                    <option value="">Todas las categorías</option>
                    @foreach (\App\Models\Garment::CATEGORIES as $key => $label)
                        <option value="{{ $key }}" @selected(($filters['category'] ?? '') === $key)>{{ $label }}</option>
                    @endforeach
                </select>

                <select class="rounded-md border border-stone-300 bg-white px-3 py-2 text-sm" name="size">
                    <option value="">Todas las tallas</option>
                    @foreach (\App\Models\Garment::SIZES as $key => $label)
                        <option value="{{ $key }}" @selected(($filters['size'] ?? '') === $key)>{{ $label }}</option>
                    @endforeach
                </select>

                <select class="rounded-md border border-stone-300 bg-white px-3 py-2 text-sm" name="color">
                    <option value="">Todos los colores</option>
                    @foreach (\App\Models\Garment::COLORS as $key => $label)
                        <option value="{{ $key }}" @selected(($filters['color'] ?? '') === $key)>{{ $label }}</option>
                    @endforeach
                </select>

                <select class="rounded-md border border-stone-300 bg-white px-3 py-2 text-sm" name="status">
                    <option value="">Todos los estados</option>
                    @foreach (\App\Models\Garment::STATUSES as $key => $label)
                        <option value="{{ $key }}" @selected(($filters['status'] ?? '') === $key)>{{ $label }}</option>
                    @endforeach
                </select>

                <div class="flex items-center gap-3">
                    <button
                        class="inline-flex items-center rounded-md bg-stone-900 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-stone-800"
                        type="submit"
                    >
                        Filtrar
                    </button>
                    <a class="text-sm font-medium text-stone-600 hover:text-stone-800" href="{{ route('garments.my') }}">
                        Limpiar
                    </a>
                </div>
            </form>
        </section>

        {{-- ===== Lista de prendas del usuario ===== --}}
        <section class="grid gap-4 sm:grid-cols-2">
            @forelse ($garments as $garment)
                @php
                    $statusColors = [
                        'available' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                        'reserved'  => 'bg-amber-100 text-amber-800 border-amber-200',
                        'sold'      => 'bg-rose-100 text-rose-800 border-rose-200',
                    ];
                    $statusClass = $statusColors[$garment->status] ?? 'bg-stone-100 text-stone-800 border-stone-200';
                @endphp

                <article class="rounded-2xl border border-stone-200 bg-white shadow-sm overflow-hidden transition hover:shadow-md">
                    <div class="flex">
                        {{-- Miniatura --}}
                        <div class="w-28 shrink-0 bg-stone-100 flex items-center justify-center">
                            @if ($garment->image_path)
                                <img
                                    src="{{ asset('storage/' . $garment->image_path) }}"
                                    alt="{{ $garment->name }}"
                                    class="h-full w-full object-cover"
                                >
                            @else
                                <svg class="h-8 w-8 text-stone-300" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0 0 22.5 18.75V5.25A2.25 2.25 0 0 0 20.25 3H3.75A2.25 2.25 0 0 0 1.5 5.25v13.5A2.25 2.25 0 0 0 3.75 21Z" />
                                </svg>
                            @endif
                        </div>

                        {{-- Info --}}
                        <div class="flex-1 p-4">
                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <h2 class="font-semibold text-stone-900">{{ $garment->name }}</h2>
                                    <p class="mt-0.5 text-xs text-stone-500">{{ $garment->categoryLabel() }}</p>
                                </div>
                                <span class="rounded-full border px-2.5 py-0.5 text-xs font-semibold {{ $statusClass }}">
                                    {{ $garment->statusLabel() }}
                                </span>
                            </div>

                            <div class="mt-2 flex flex-wrap items-center gap-2 text-xs text-stone-500">
                                <span class="rounded-full bg-stone-100 px-2.5 py-0.5">{{ $garment->sizeLabel() }}</span>
                                <span class="rounded-full bg-stone-100 px-2.5 py-0.5">{{ $garment->colorLabel() }}</span>
                                <span class="rounded-full bg-emerald-100 px-2.5 py-0.5 font-semibold text-emerald-700">
                                    ${{ number_format((float) $garment->price, 2) }}
                                </span>
                            </div>

                            {{-- Acciones del propietario --}}
                            <div class="mt-3 flex flex-wrap items-center gap-2">
                                <a
                                    class="inline-flex items-center rounded-md border border-stone-200 px-3 py-1.5 text-xs font-semibold text-stone-700 transition hover:border-stone-300 hover:bg-stone-50"
                                    href="{{ route('garments.show', $garment) }}"
                                >
                                    Ver
                                </a>
                                <a
                                    class="inline-flex items-center rounded-md border border-emerald-200 px-3 py-1.5 text-xs font-semibold text-emerald-700 transition hover:border-emerald-300 hover:bg-emerald-50"
                                    href="{{ route('garments.edit', $garment) }}"
                                >
                                    Editar
                                </a>

                                {{-- Cambio rápido de estado --}}
                                @if ($garment->status === 'available')
                                    <form method="POST" action="{{ route('garments.updateStatus', $garment) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="reserved">
                                        <button type="submit" class="inline-flex items-center rounded-md border border-amber-200 px-3 py-1.5 text-xs font-semibold text-amber-700 transition hover:bg-amber-50">
                                            Reservar
                                        </button>
                                    </form>
                                @elseif ($garment->status === 'reserved')
                                    <form method="POST" action="{{ route('garments.updateStatus', $garment) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="sold">
                                        <button type="submit" class="inline-flex items-center rounded-md border border-rose-200 px-3 py-1.5 text-xs font-semibold text-rose-700 transition hover:bg-rose-50">
                                            Vendida
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('garments.updateStatus', $garment) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="available">
                                        <button type="submit" class="inline-flex items-center rounded-md border border-emerald-200 px-3 py-1.5 text-xs font-semibold text-emerald-700 transition hover:bg-emerald-50">
                                            Disponible
                                        </button>
                                    </form>
                                @endif

                                {{-- Eliminar — abre modal de confirmación --}}
                                <button
                                    type="button"
                                    class="ml-auto text-xs font-semibold text-rose-600 hover:text-rose-700 transition"
                                    onclick="document.getElementById('modal-eliminar-{{ $garment->id }}').classList.remove('hidden')"
                                >
                                    Eliminar
                                </button>
                            </div>
                        </div>
                    </div>
                </article>
            @empty
                <div class="sm:col-span-2 rounded-2xl border border-dashed border-stone-300 bg-white p-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-stone-300 mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                    </svg>
                    <p class="text-sm text-stone-500">Aún no tienes prendas publicadas.</p>
                    <a
                        href="{{ route('garments.create') }}"
                        class="mt-3 inline-flex text-sm font-semibold text-emerald-600 hover:text-emerald-700"
                    >
                        ¡Publica tu primera prenda!
                    </a>
                </div>
            @endforelse
        </section>

        {{-- ===== Paginación ===== --}}
        @if ($garments->hasPages())
            <div class="mt-2">
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
            <div class="mx-4 w-full max-w-md rounded-2xl border border-stone-200 bg-white p-6 shadow-xl">
                <div class="flex items-start gap-4">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-rose-100">
                        <svg class="h-5 w-5 text-rose-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-stone-900">Eliminar prenda</h3>
                        <p class="mt-2 text-sm text-stone-600">
                            ¿Estás seguro de que deseas eliminar <strong>{{ $garment->name }}</strong>?
                            Esta acción es permanente y no se puede deshacer.
                        </p>
                    </div>
                </div>
                <div class="mt-6 flex items-center justify-end gap-3">
                    <button
                        type="button"
                        class="rounded-md px-4 py-2 text-sm font-medium text-stone-600 transition hover:bg-stone-100"
                        onclick="document.getElementById('modal-eliminar-{{ $garment->id }}').classList.add('hidden')"
                    >
                        Cancelar
                    </button>
                    <form method="POST" action="{{ route('garments.destroy', $garment) }}">
                        @csrf
                        @method('DELETE')
                        <button
                            type="submit"
                            class="inline-flex items-center rounded-md bg-rose-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-rose-700"
                        >
                            Sí, eliminar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endsection
