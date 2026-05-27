<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="ReClóset — Tu armario digital para compartir y descubrir prendas de vestir.">
        <title>@yield('title', config('app.name', 'ReClóset'))</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="min-h-screen bg-white text-stone-900 relative overflow-x-hidden">
        {{-- Elegant global mesh background blur circles (Login background for the entire app) --}}
        <div class="fixed top-0 left-0 w-full h-full pointer-events-none z-0 overflow-hidden">
            <div class="absolute -top-24 -left-24 w-[35rem] h-[35rem] rounded-full bg-[#7fc8f8]/20 blur-3xl"></div>
            <div class="absolute top-1/4 -right-24 w-[30rem] h-[30rem] rounded-full bg-[#ffe45e]/15 blur-3xl"></div>
            <div class="absolute bottom-10 left-1/3 w-[35rem] h-[35rem] rounded-full bg-[#5aa9e6]/15 blur-3xl"></div>
        </div>

        {{-- ======= Navegación principal ======= --}}
        <header class="sticky top-0 z-50 bg-white/80 backdrop-blur-md border-b border-stone-100 py-4 px-6 md:px-12 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-6">
                <a href="{{ route('home') }}" class="group inline-flex items-center">
                    <img src="{{ asset('full-logo-app.png') }}" class="h-10 w-auto object-contain" alt="ReClóset Logo">
                </a>
                <nav class="flex items-center gap-1 bg-stone-50/80 backdrop-blur-sm p-1 rounded-2xl border border-stone-200/50">
                    <a
                        href="{{ route('garments.explore') }}"
                        class="rounded-xl px-4 py-2.5 text-xs font-bold transition inline-flex items-center gap-1.5
                            {{ request()->routeIs('garments.explore') || request()->routeIs('home')
                                ? 'bg-emerald-500 text-white shadow-sm'
                                : 'text-stone-600 hover:bg-stone-100 hover:text-stone-800' }}"
                    >
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.602 10.602Z" />
                        </svg>
                        Explorar
                    </a>
                    <a
                        href="{{ route('garments.my') }}"
                        class="rounded-xl px-4 py-2.5 text-xs font-bold transition inline-flex items-center gap-1.5
                            {{ request()->routeIs('garments.my')
                                ? 'bg-emerald-500 text-white shadow-sm'
                                : 'text-stone-600 hover:bg-stone-100 hover:text-stone-800' }}"
                    >
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                        </svg>
                        Mis Prendas
                    </a>
                    @auth
                    <a
                        href="{{ route('chat.index') }}"
                        class="rounded-xl px-4 py-2.5 text-xs font-bold transition inline-flex items-center gap-1.5
                            {{ request()->routeIs('chat.index') || request()->routeIs('chat.show')
                                ? 'bg-emerald-500 text-white shadow-sm'
                                : 'text-stone-600 hover:bg-stone-100 hover:text-stone-800' }}"
                    >
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 0 1-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8Z" />
                        </svg>
                        Mensajes
                    </a>
                    @endauth
                </nav>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                @auth
                    <span class="hidden text-xs font-bold text-stone-600 sm:inline inline-flex items-center gap-1 bg-stone-100 px-3 py-2 rounded-2xl border border-stone-200 shadow-sm">
                        {{ auth()->user()->name }}
                    </span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button
                            class="rounded-xl border border-stone-200 bg-white px-4 py-2.5 text-xs font-bold text-stone-700 shadow-md transition hover:border-stone-300 hover:bg-stone-50 inline-flex items-center gap-1.5 cursor-pointer"
                            type="submit"
                        >
                            <svg class="h-4 w-4 text-rose-500" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75" />
                            </svg>
                            Cerrar sesion
                        </button>
                    </form>
                @else
                    <a
                        class="rounded-xl px-4 py-2.5 text-xs font-bold text-stone-600 transition hover:bg-stone-100 hover:text-stone-800 inline-flex items-center gap-1.5 cursor-pointer"
                        href="{{ route('login') }}"
                    >
                        <svg class="h-4 w-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                        </svg>
                        Iniciar sesion
                    </a>
                    <a
                        class="rounded-xl bg-emerald-500 px-4 py-2.5 text-xs font-bold text-white shadow-md transition hover:bg-emerald-600 inline-flex items-center gap-1.5 cursor-pointer"
                        href="{{ route('register') }}"
                    >
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
                        </svg>
                        Registrarse
                    </a>
                @endauth
                @yield('actions')
            </div>
        </header>

        <div class="relative z-10 mx-auto flex min-h-screen w-full max-w-none flex-col px-6 md:px-12 pb-8 pt-4">

            {{-- ======= Page title ======= --}}
            @hasSection('page_title')
                <div class="mt-8 relative z-10">
                    <h1 class="text-3xl font-extrabold text-stone-900 tracking-tight">@yield('page_title')</h1>
                </div>
            @endif

            {{-- ======= Success Modal ======= --}}
            @if (session('status'))
                <div
                    id="success-modal"
                    class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm transition-opacity duration-300"
                    role="dialog"
                    aria-modal="true"
                >
                    <div 
                        class="mx-4 w-full max-w-sm rounded-3xl border border-stone-200 bg-white/95 backdrop-blur-md p-6 shadow-2xl flex flex-col items-center text-center gap-4 transition-all duration-300 scale-100 relative z-50"
                        onclick="event.stopPropagation()"
                    >
                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-500 border border-emerald-100 shadow-inner">
                            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-stone-900">¡Confirmación!</h3>
                            <p class="mt-2 text-sm text-stone-600 font-medium leading-relaxed">
                                {{ session('status') }}
                            </p>
                        </div>
                        <button
                            type="button"
                            class="w-full mt-2 inline-flex items-center justify-center rounded-xl bg-stone-900 px-4 py-2.5 text-xs font-bold text-white shadow-md transition hover:bg-stone-850 cursor-pointer uppercase tracking-wider"
                            onclick="document.getElementById('success-modal').classList.add('hidden')"
                        >
                            Aceptar
                        </button>
                    </div>
                </div>
                <script>
                    document.addEventListener('keydown', function(e) {
                        if (e.key === 'Escape') {
                            const modal = document.getElementById('success-modal');
                            if (modal) modal.classList.add('hidden');
                        }
                    });
                    document.getElementById('success-modal').addEventListener('click', function() {
                        this.classList.add('hidden');
                    });
                </script>
            @endif

            {{-- ======= Contenido principal ======= --}}
            <main class="mt-8 flex-1 relative z-10 flex flex-col">
                @yield('content')
            </main>

            {{-- ======= Footer ======= --}}
            <!--
<footer class="mt-16 border-t border-stone-150 py-6 text-center text-xs text-stone-400 relative z-10">
    &copy; {{ date('Y') }} ReClóset — Todos los derechos reservados.
</footer>
-->
        </div>
    </body>
</html>
