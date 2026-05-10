{{--
    Vista "Explorar" — Galería pública de prendas disponibles.
    Solo muestra prendas de otros usuarios con status "available".
    No se muestran botones de Editar/Eliminar.
--}}

@extends('layouts.app')

@section('title', 'Explorar prendas — ReClóset')
@section('page_title', 'Explorar')
@section('page_subtitle', 'Descubre prendas disponibles de otros usuarios.')

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

        {{-- ===== Filtros ===== --}}
        <section class="rounded-2xl border border-stone-200 bg-white p-5 shadow-sm">
            <form class="grid gap-4 sm:grid-cols-4" method="GET" action="{{ route('garments.explore') }}">

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

                <div class="flex items-center gap-3">
                    <button
                        class="inline-flex items-center rounded-md bg-stone-900 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-stone-800"
                        type="submit"
                    >
                        Filtrar
                    </button>
                    <a class="text-sm font-medium text-stone-600 hover:text-stone-800" href="{{ route('garments.explore') }}">
                        Limpiar
                    </a>
                </div>
            </form>
        </section>

        {{-- ===== Galería de prendas ===== --}}
        <section class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($garments as $garment)
                <a
                    href="{{ route('garments.show', $garment) }}"
                    class="group rounded-2xl border border-stone-200 bg-white shadow-sm overflow-hidden transition hover:shadow-md hover:border-stone-300"
                >
                    {{-- Imagen --}}
                    <div class="aspect-[4/3] bg-stone-100 overflow-hidden">
                        @if ($garment->image_path)
                            <img
                                src="{{ asset('storage/' . $garment->image_path) }}"
                                alt="{{ $garment->name }}"
                                class="h-full w-full object-cover transition group-hover:scale-105"
                            >
                        @else
                            <div class="flex h-full items-center justify-center text-stone-300">
                                <svg class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0 0 22.5 18.75V5.25A2.25 2.25 0 0 0 20.25 3H3.75A2.25 2.25 0 0 0 1.5 5.25v13.5A2.25 2.25 0 0 0 3.75 21Z" />
                                </svg>
                            </div>
                        @endif
                    </div>

                    {{-- Info --}}
                    <div class="p-4">
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <h3 class="font-semibold text-stone-900 group-hover:text-emerald-700 transition">{{ $garment->name }}</h3>
                                <p class="mt-0.5 text-xs text-stone-500">{{ $garment->categoryLabel() }}</p>
                            </div>
                            <span class="rounded-full bg-emerald-100 px-3 py-1 text-sm font-bold text-emerald-800 shrink-0">
                                ${{ number_format((float) $garment->price, 2) }}
                            </span>
                        </div>

                        <div class="mt-3 flex flex-wrap gap-2">
                            <span class="rounded-full bg-stone-100 px-2.5 py-0.5 text-xs text-stone-600">{{ $garment->sizeLabel() }}</span>
                            <span class="rounded-full bg-stone-100 px-2.5 py-0.5 text-xs text-stone-600">{{ $garment->colorLabel() }}</span>
                        </div>
                    </div>
                </a>
            @empty
                <div class="sm:col-span-2 lg:col-span-3 rounded-2xl border border-dashed border-stone-300 bg-white p-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-stone-300 mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                    </svg>
                    <p class="text-sm text-stone-500">No hay prendas disponibles en este momento.</p>
                    <a
                        href="{{ route('garments.create') }}"
                        class="mt-3 inline-flex text-sm font-semibold text-emerald-600 hover:text-emerald-700"
                    >
                        ¡Publica la primera!
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
@endsection
