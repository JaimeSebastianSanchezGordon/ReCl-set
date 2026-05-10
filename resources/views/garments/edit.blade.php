@extends('layouts.app')

@section('title', 'Editar prenda — ReClóset')
@section('page_title', 'Editar prenda')
@section('page_subtitle', 'Modifica los datos de tu prenda. Puedes cambiar el estado a Reservada o Vendida.')

@section('actions')
    <a class="text-sm font-semibold text-stone-600 hover:text-stone-800 transition" href="{{ route('garments.show', $garment) }}">
        Ver prenda
    </a>
    <a class="text-sm font-semibold text-stone-600 hover:text-stone-800 transition" href="{{ route('garments.my') }}">
        ← Mis Prendas
    </a>
@endsection

@section('content')
    <form
        class="rounded-2xl border border-stone-200 bg-white p-6 shadow-sm"
        method="POST"
        action="{{ route('garments.update', $garment) }}"
        enctype="multipart/form-data"
    >
        @csrf
        @method('PUT')
        @include('garments._form', ['garment' => $garment, 'isEdit' => true])

        <div class="mt-6 flex items-center gap-3">
            <button
                class="inline-flex items-center rounded-md bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700 focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2"
                type="submit"
            >
                Guardar cambios
            </button>
            <a class="text-sm font-medium text-stone-600 hover:text-stone-800 transition" href="{{ route('garments.show', $garment) }}">
                Cancelar
            </a>
        </div>
    </form>
@endsection
