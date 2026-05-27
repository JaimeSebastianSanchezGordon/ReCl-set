@extends('layouts.app')

@section('title', 'Nueva contrasena - ReCloset')

@section('content')
<div class="relative py-8 flex items-center justify-center overflow-hidden w-full">
    <section class="w-full max-w-md glass-card rounded-3xl p-8 relative z-10 transition duration-300">
        <!-- Logo inside Card -->
        <div class="flex flex-col items-center mb-6">
            <img src="{{ asset('full-logo-app.png') }}" class="h-12 w-auto object-contain mb-2" alt="ReClóset Logo">
            <h2 class="text-xl font-extrabold text-stone-900 tracking-tight">Nueva contraseña</h2>
            <p class="text-xs text-stone-500 font-medium text-center mt-1">Usa el enlace recibido para proteger nuevamente tu cuenta.</p>
        </div>

        <form class="grid gap-5" method="POST" action="{{ route('password.update') }}">
            @csrf

            <input name="token" type="hidden" value="{{ $token }}">

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
                    value="{{ old('email', $email) }}"
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
                    Nueva contraseña
                </label>
                <input
                    class="rounded-xl border border-stone-250 bg-white/80 backdrop-blur-sm px-4 py-2.5 text-sm shadow-sm transition focus:border-[#5aa9e6] focus:ring-1 focus:ring-[#5aa9e6] focus:outline-none font-medium"
                    id="password"
                    name="password"
                    type="password"
                    autocomplete="new-password"
                    required
                >
                @error('password')
                    <p class="text-xs text-rose-600 font-bold mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid gap-2">
                <label class="text-xs font-bold text-stone-700 inline-flex items-center gap-1.5 uppercase tracking-wider" for="password_confirmation">
                    <svg class="h-4 w-4 text-[#5aa9e6]" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z" />
                    </svg>
                    Confirmar nueva contraseña
                </label>
                <input
                    class="rounded-xl border border-stone-250 bg-white/80 backdrop-blur-sm px-4 py-2.5 text-sm shadow-sm transition focus:border-[#5aa9e6] focus:ring-1 focus:ring-[#5aa9e6] focus:outline-none font-medium"
                    id="password_confirmation"
                    name="password_confirmation"
                    type="password"
                    autocomplete="new-password"
                    required
                >
            </div>

            <button
                class="mt-2 inline-flex items-center justify-center rounded-xl bg-[#5aa9e6] px-4 py-3.5 text-xs font-bold text-white shadow-md transition hover:bg-[#3a8fcb] focus:ring-2 focus:ring-[#5aa9e6] focus:ring-offset-2 gap-2 cursor-pointer uppercase tracking-widest"
                type="submit"
            >
                <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z" />
                </svg>
                Actualizar contraseña
            </button>
        </form>
    </section>
</div>
@endsection
