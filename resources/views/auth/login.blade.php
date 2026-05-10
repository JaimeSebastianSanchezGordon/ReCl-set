@extends('layouts.app')

@section('title', 'Iniciar sesion - ReCloset')
@section('page_title', 'Iniciar sesion')
@section('page_subtitle', 'Entra a tu cuenta para publicar y gestionar tus prendas.')

@section('content')
    <section class="mx-auto max-w-md rounded-2xl border border-stone-200 bg-white p-6 shadow-sm">
        <form class="grid gap-5" method="POST" action="{{ route('login.store') }}">
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

            <div class="grid gap-2">
                <label class="text-sm font-medium text-stone-700" for="password">Contrasena</label>
                <input
                    class="rounded-md border border-stone-300 bg-white px-3 py-2 text-sm shadow-sm transition focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 focus:outline-none"
                    id="password"
                    name="password"
                    type="password"
                    autocomplete="current-password"
                    required
                >
                @error('password')
                    <p class="text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <label class="flex items-center gap-2 text-sm text-stone-600">
                <input class="rounded border-stone-300 text-emerald-600 focus:ring-emerald-500" name="remember" type="checkbox" value="1">
                Recordarme
            </label>

            <button
                class="inline-flex items-center justify-center rounded-md bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700 focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2"
                type="submit"
            >
                Entrar
            </button>

            <p class="text-center text-sm text-stone-500">
                No tienes cuenta?
                <a class="font-semibold text-emerald-700 hover:text-emerald-800" href="{{ route('register') }}">Registrate</a>
            </p>
        </form>
    </section>
@endsection
