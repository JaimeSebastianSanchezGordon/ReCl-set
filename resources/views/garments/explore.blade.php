{{--
    Vista "Explorar" — Galería pública de prendas disponibles.
    Solo muestra prendas de otros usuarios con status "available".
    No se muestran botones de Editar/Eliminar.
--}}

@extends('layouts.app')

@section('title', 'Explorar prendas — ReClóset')
@section('content')
    <div class="grid gap-6">

        {{-- Header row with Title and Inline Filters --}}
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 relative z-20 mt-2">
            <h1 class="text-3xl font-extrabold text-stone-900 tracking-tight shrink-0">Explorar prendas</h1>

            <form class="flex flex-col sm:flex-row sm:items-center gap-2 w-full lg:w-auto" method="GET" action="{{ route('garments.explore') }}">
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

                    @if(!empty($filters['category']) || !empty($filters['size']) || !empty($filters['color']))
                        <a class="text-xs font-bold text-stone-600 hover:text-stone-850 transition inline-flex items-center gap-1 uppercase tracking-wider cursor-pointer px-2 py-1.5" href="{{ route('garments.explore') }}">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                            </svg>
                            Limpiar
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- ===== Galería de prendas ===== --}}
        <section class="grid gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 relative z-10">
            @forelse ($garments as $garment)
                <a
                    href="{{ route('garments.show', $garment) }}"
                    class="group rounded-3xl border border-stone-200/80 bg-white/80 backdrop-blur-sm shadow-md overflow-hidden transition-all duration-300 hover:shadow-xl hover:border-[#5aa9e6]/50"
                >
                    {{-- Imagen --}}
                    <div class="aspect-[4/3] bg-stone-50 overflow-hidden relative border-b border-stone-100">
                        @if ($garment->image_path)
                            <img
                                src="{{ asset('storage/' . $garment->image_path) }}"
                                alt="{{ $garment->name }}"
                                class="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                            >
                        @else
                            <div class="flex h-full items-center justify-center text-stone-300">
                                <svg class="h-14 w-14" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0 0 22.5 18.75V5.25A2.25 2.25 0 0 0 20.25 3H3.75A2.25 2.25 0 0 0 1.5 5.25v13.5A2.25 2.25 0 0 0 3.75 21Z" />
                                </svg>
                            </div>
                        @endif
                    </div>

                    {{-- Info --}}
                    <div class="p-5">
                        <div class="flex items-start justify-between gap-2">
                            <div class="min-w-0">
                                <h3 class="font-bold text-stone-900 group-hover:text-[#2974a6] transition truncate leading-snug">{{ $garment->name }}</h3>
                                <p class="mt-0.5 text-xs text-stone-400 font-medium">{{ $garment->categoryLabel() }}</p>
                            </div>
                            <span class="rounded-2xl bg-emerald-50 border border-emerald-100 px-3.5 py-1 text-sm font-black text-emerald-800 shrink-0">
                                ${{ number_format((float) $garment->price, 2) }}
                            </span>
                        </div>

                        <div class="mt-4 flex flex-wrap gap-2">
                            <span class="rounded-full bg-stone-100 px-3 py-1 text-[10px] font-bold text-stone-600 uppercase tracking-wider border border-stone-200/50">{{ $garment->sizeLabel() }}</span>
                            <span class="rounded-full bg-stone-100 px-3 py-1 text-[10px] font-bold text-stone-600 uppercase tracking-wider border border-stone-200/50">{{ $garment->colorLabel() }}</span>
                        </div>
                    </div>
                </a>
            @empty
                <div class="sm:col-span-2 lg:col-span-3 rounded-3xl border border-dashed border-stone-300 bg-white/70 backdrop-blur-md p-16 text-center shadow-lg relative z-10 glass-card">
                    <svg class="mx-auto h-16 w-16 text-stone-300 mb-4" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                    </svg>
                    <h3 class="text-lg font-bold text-stone-900">No hay prendas en este momento</h3>
                    <p class="text-sm text-stone-500 max-w-sm mx-auto mt-2">Sé el primero en publicar una prenda en nuestro catálogo digital.</p>
                    <a
                        href="{{ route('garments.create') }}"
                        class="mt-6 inline-flex items-center rounded-xl bg-emerald-600 px-5 py-3 text-xs font-bold text-white shadow-md transition hover:bg-emerald-700 gap-1.5 uppercase tracking-wider"
                    >
                        <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        ¡Publica tu prenda!
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
@endsection
