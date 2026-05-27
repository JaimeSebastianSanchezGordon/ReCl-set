@extends('layouts.app')

@section('title', 'Recuperar contrasena - ReCloset')
@section('page_title', 'Recuperar contrasena')
@section('page_subtitle', 'Ingresa tu correo y te enviaremos un enlace seguro para crear una nueva contrasena.')

@section('content')
    <section class="mx-auto max-w-md rounded-2xl border border-stone-200 bg-white p-6 shadow-sm">
        <form class="grid gap-5" method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="grid gap-2">
                <label class="text-sm font-medium text-stone-700" for="email">Correo electronico</label>
                <input
                    class="rounded-md border border-stone-300 bg-white px-3 py-2 text-sm shadow-sm transition focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 focus:outline-none"
                    id="email"
                    name="email"
                    type="email"
                    value="{{ old('email') }}"
                    autocomplete="email"
                    required
                    autofocus
                >
                @error('email')
                    <p class="text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <button
                class="inline-flex items-center justify-center rounded-md bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700 focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2"
                type="submit"
            >
                Enviar enlace
            </button>

            <p class="text-center text-sm text-stone-500">
                Ya recordaste tu contrasena?
                <a class="font-semibold text-emerald-700 hover:text-emerald-800" href="{{ route('login') }}">Inicia sesion</a>
            </p>
        </form>
    </section>
@endsection
