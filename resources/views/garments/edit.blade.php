@extends('layouts.app')

@section('title', 'Editar prenda — ReClóset')
@section('page_title', 'Editar prenda')
@section('page_subtitle', 'Modifica los datos de tu prenda. Puedes cambiar el estado a Reservada o Vendida.')

@section('actions')
    <a class="text-xs font-bold text-stone-600 hover:text-stone-850 transition inline-flex items-center gap-1.5 cursor-pointer uppercase tracking-wider" href="{{ route('garments.show', $garment) }}">
        Ver prenda
    </a>
    <a class="text-xs font-bold text-stone-600 hover:text-stone-850 transition inline-flex items-center gap-1.5 cursor-pointer uppercase tracking-wider" href="{{ route('garments.my') }}">
        <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
        </svg>
        Mis prendas
    </a>
@endsection

@section('content')
    <form
        class="rounded-3xl border border-stone-200/80 bg-white/70 backdrop-blur-sm p-8 shadow-lg relative z-10 max-w-2xl mx-auto"
        method="POST"
        action="{{ route('garments.update', $garment) }}"
        enctype="multipart/form-data"
    >
        @csrf
        @method('PUT')
        @include('garments._form', ['garment' => $garment, 'isEdit' => true])

        <div class="mt-8 flex items-center gap-3 border-t border-stone-150 pt-6">
            <button
                class="inline-flex items-center rounded-xl bg-emerald-600 px-5 py-3 text-xs font-bold text-white shadow-md transition hover:bg-emerald-700 focus:ring-2 focus:ring-emerald-550 focus:ring-offset-2 gap-1.5 cursor-pointer uppercase tracking-wider"
                type="submit"
            >
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                </svg>
                Guardar cambios
            </button>
            <a class="text-xs font-bold text-stone-600 hover:text-stone-850 transition inline-flex items-center gap-1 uppercase tracking-wider cursor-pointer" href="{{ route('garments.show', $garment) }}">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
                Cancelar
            </a>
        </div>
    </form>
@endsection
