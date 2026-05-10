@extends('layouts.app')

@section('title', $garment->name . ' — ReClóset')
@section('page_title', 'Detalle de prenda')

@section('actions')
    <a class="text-sm font-semibold text-stone-600 hover:text-stone-800 transition" href="{{ route('garments.explore') }}">
        ← Explorar
    </a>
    @if ($isOwner)
        <a
            class="inline-flex items-center rounded-md border border-emerald-200 bg-emerald-50 px-3 py-2 text-xs font-semibold text-emerald-700 transition hover:bg-emerald-100"
            href="{{ route('garments.edit', $garment) }}"
        >
            Editar
        </a>
    @endif
@endsection

@section('content')
    <section class="rounded-2xl border border-stone-200 bg-white shadow-sm overflow-hidden">

        {{-- ===== Imagen + Info principal ===== --}}
        <div class="flex flex-col md:flex-row">

            {{-- Imagen --}}
            <div class="md:w-1/3 bg-stone-100 flex items-center justify-center min-h-[240px]">
                @if ($garment->image_path)
                    <img
                        src="{{ asset('storage/' . $garment->image_path) }}"
                        alt="{{ $garment->name }}"
                        class="h-full w-full object-cover"
                    >
                @else
                    <div class="flex flex-col items-center justify-center text-stone-400 p-8">
                        <svg class="h-16 w-16 mb-2" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0 0 22.5 18.75V5.25A2.25 2.25 0 0 0 20.25 3H3.75A2.25 2.25 0 0 0 1.5 5.25v13.5A2.25 2.25 0 0 0 3.75 21Z" />
                        </svg>
                        <span class="text-xs">Sin imagen</span>
                    </div>
                @endif
            </div>

            {{-- Datos principales --}}
            <div class="flex-1 p-6">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h2 class="text-2xl font-semibold text-stone-900">{{ $garment->name }}</h2>
                        <p class="mt-1 text-sm text-stone-500">{{ $garment->categoryLabel() }}</p>
                    </div>
                    <div class="flex flex-col items-end gap-2">
                        <span class="rounded-full bg-emerald-100 px-4 py-2 text-lg font-bold text-emerald-800">
                            ${{ number_format((float) $garment->price, 2) }}
                        </span>
                        @php
                            $statusColors = [
                                'available' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                'reserved'  => 'bg-amber-100 text-amber-800 border-amber-200',
                                'sold'      => 'bg-rose-100 text-rose-800 border-rose-200',
                            ];
                            $statusClass = $statusColors[$garment->status] ?? 'bg-stone-100 text-stone-800 border-stone-200';
                        @endphp
                        <span class="rounded-full border px-3 py-1 text-xs font-semibold {{ $statusClass }}">
                            {{ $garment->statusLabel() }}
                        </span>
                    </div>
                </div>

                <p class="mt-4 text-sm leading-relaxed text-stone-600">
                    {{ $garment->description ?? 'Sin descripción disponible.' }}
                </p>

                {{-- Atributos --}}
                <div class="mt-6 grid gap-3 sm:grid-cols-2">
                    <div class="rounded-xl border border-stone-200 bg-stone-50 p-3">
                        <p class="text-xs uppercase tracking-wider text-stone-500">Talla</p>
                        <p class="mt-1 text-sm font-semibold text-stone-800">{{ $garment->sizeLabel() }}</p>
                    </div>
                    <div class="rounded-xl border border-stone-200 bg-stone-50 p-3">
                        <p class="text-xs uppercase tracking-wider text-stone-500">Color</p>
                        <p class="mt-1 text-sm font-semibold text-stone-800">{{ $garment->colorLabel() }}</p>
                    </div>
                </div>

                {{-- Acciones del propietario --}}
                @if ($isOwner)
                    <div class="mt-6 flex flex-wrap items-center gap-3 border-t border-stone-200 pt-6">
                        <a
                            class="inline-flex items-center rounded-md bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700"
                            href="{{ route('garments.edit', $garment) }}"
                        >
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
                                    class="inline-flex items-center rounded-md border border-amber-200 bg-amber-50 px-4 py-2 text-sm font-semibold text-amber-700 transition hover:bg-amber-100"
                                >
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
                                    class="inline-flex items-center rounded-md border border-rose-200 bg-rose-50 px-4 py-2 text-sm font-semibold text-rose-700 transition hover:bg-rose-100"
                                >
                                    Marcar como Vendida
                                </button>
                            </form>
                            <form method="POST" action="{{ route('garments.updateStatus', $garment) }}">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="available">
                                <button
                                    type="submit"
                                    class="inline-flex items-center rounded-md border border-emerald-200 bg-emerald-50 px-4 py-2 text-sm font-semibold text-emerald-700 transition hover:bg-emerald-100"
                                >
                                    Regresar a Disponible
                                </button>
                            </form>
                        @endif

                        {{-- Botón que abre el modal de confirmación --}}
                        <button
                            type="button"
                            class="ml-auto text-sm font-semibold text-rose-600 hover:text-rose-700 transition"
                            onclick="document.getElementById('modal-eliminar-{{ $garment->id }}').classList.remove('hidden')"
                        >
                            Eliminar
                        </button>
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
                            Esta acción no se puede deshacer y se eliminará también la imagen asociada.
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
    @endif
@endsection
