@extends('layouts.app')

@section('title', 'Iniciar sesion - ReCloset')

@section('content')
<div class="relative py-8 flex items-center justify-center overflow-hidden w-full">
    <section class="w-full max-w-md glass-card rounded-3xl p-8 relative z-10 transition duration-300">
        <!-- Logo inside Card -->
        <div class="flex flex-col items-center mb-6">
            <img src="{{ asset('full-logo-app.png') }}" class="h-20 w-auto object-contain mb-2" alt="ReClóset Logo">
            <h2 class="text-xl font-extrabold text-stone-900 tracking-tight">Bienvenido de nuevo</h2>
            <p class="text-xs text-stone-500 font-medium text-center mt-1">Entra a tu cuenta para publicar y gestionar tus prendas.</p>
        </div>

        <form class="grid gap-5" method="POST" action="{{ route('login.store') }}">
            @csrf

            <div class="grid gap-2">
                <label class="text-xs font-bold text-stone-700 inline-flex items-center gap-1.5 uppercase tracking-wider" for="email">
                    <svg class="h-4 w-4 text-[#5aa9e6]" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                    </svg>
                    Correo electrónico
                </label>
                <input
                    class="rounded-xl border border-stone-250 bg-white/80 backdrop-blur-sm px-4 py-2.5 text-sm shadow-sm transition focus:border-[#5aa9e6] focus:ring-1 focus:ring-[#5aa9e6] focus:outline-none font-medium"
                    id="email"
                    name="email"
                    type="email"
                    value="{{ old('email') }}"
                    autocomplete="email"
                    required
                    autofocus
                >
                @error('email')
                    <p class="text-xs text-rose-600 font-bold mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid gap-2">
                <label class="text-xs font-bold text-stone-700 inline-flex items-center gap-1.5 uppercase tracking-wider" for="password">
                    <svg class="h-4 w-4 text-[#5aa9e6]" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0V10.5m-3 1.125c0-.621.504-1.125 1.125-1.125h12.75c.621 0 1.125.504 1.125 1.125v9a1.125 1.125 0 0 1-1.125 1.125H5.625a1.125 1.125 0 0 1-1.125-1.125v-9Z" />
                    </svg>
                    Contraseña
                </label>
                <input
                    class="rounded-xl border border-stone-250 bg-white/80 backdrop-blur-sm px-4 py-2.5 text-sm shadow-sm transition focus:border-[#5aa9e6] focus:ring-1 focus:ring-[#5aa9e6] focus:outline-none font-medium"
                    id="password"
                    name="password"
                    type="password"
                    autocomplete="current-password"
                    required
                >
                @error('password')
                    <p class="text-xs text-rose-600 font-bold mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between gap-2 mt-1">
                <label class="flex items-center gap-2 text-xs font-bold text-stone-600 cursor-pointer select-none uppercase tracking-wider">
                    <input class="rounded border-stone-300 text-[#5aa9e6] focus:ring-[#5aa9e6] h-4 w-4 cursor-pointer" name="remember" type="checkbox" value="1">
                    Recordarme
                </label>
                <a class="text-xs font-bold text-[#2974a6] hover:text-[#1d577e] transition" href="{{ route('password.request') }}">
                    Olvide mi contraseña
                </a>
            </div>

            <button
                class="mt-2 inline-flex items-center justify-center rounded-xl bg-[#5aa9e6] px-4 py-3.5 text-xs font-bold text-white shadow-md transition hover:bg-[#3a8fcb] focus:ring-2 focus:ring-[#5aa9e6] focus:ring-offset-2 gap-2 cursor-pointer uppercase tracking-widest"
                type="submit"
            >
                <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75" />
                </svg>
                Ingresar al sistema
            </button>

            <p class="text-center text-xs font-bold text-stone-500 mt-2 uppercase tracking-wider">
                ¿No tienes cuenta?
                <a class="text-[#2974a6] hover:text-[#1d577e] transition" href="{{ route('register') }}">Registrate</a>
            </p>
        </form>
    </section>
</div>
@endsection
