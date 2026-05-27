<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="ReClóset — Tu armario digital para compartir y descubrir prendas de vestir.">
        <title>@yield('title', config('app.name', 'ReClóset'))</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="min-h-screen bg-gradient-to-br from-stone-50 via-amber-50 to-emerald-50 text-stone-900">
        <div class="mx-auto flex min-h-screen max-w-6xl flex-col px-6 py-8">

            {{-- ======= Navegación principal ======= --}}
            <header class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center gap-6">
                    <a href="{{ route('home') }}" class="group flex items-center gap-2">
                        <span class="text-xl font-bold text-emerald-700 transition group-hover:text-emerald-600">ReClóset</span>
                    </a>
                    <nav class="flex items-center gap-1">
                        <a
                            href="{{ route('garments.explore') }}"
                            class="rounded-lg px-3 py-2 text-sm font-medium transition
                                {{ request()->routeIs('garments.explore') || request()->routeIs('home')
                                    ? 'bg-emerald-100 text-emerald-800'
                                    : 'text-stone-600 hover:bg-stone-100 hover:text-stone-800' }}"
                        >
                            Explorar
                        </a>
                        <a
                            href="{{ route('garments.my') }}"
                            class="rounded-lg px-3 py-2 text-sm font-medium transition
                                {{ request()->routeIs('garments.my')
                                    ? 'bg-emerald-100 text-emerald-800'
                                    : 'text-stone-600 hover:bg-stone-100 hover:text-stone-800' }}"
                        >
                            Mis Prendas
                        </a>
                        @auth
                        <a
                            href="{{ route('chat.index') }}"
                            class="rounded-lg px-3 py-2 text-sm font-medium transition
                                {{ request()->routeIs('chat.index') || request()->routeIs('chat.show')
                                    ? 'bg-emerald-100 text-emerald-800'
                                    : 'text-stone-600 hover:bg-stone-100 hover:text-stone-800' }}"
                        >
                            Mensajes
                        </a>
                        @endauth
                    </nav>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    @auth
                        <span class="hidden text-sm font-medium text-stone-500 sm:inline">
                            {{ auth()->user()->name }}
                        </span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button
                                class="rounded-md border border-stone-200 bg-white px-3 py-2 text-sm font-semibold text-stone-700 shadow-sm transition hover:border-stone-300 hover:bg-stone-50"
                                type="submit"
                            >
                                Cerrar sesion
                            </button>
                        </form>
                    @else
                        <a
                            class="rounded-md px-3 py-2 text-sm font-semibold text-stone-600 transition hover:bg-stone-100 hover:text-stone-800"
                            href="{{ route('login') }}"
                        >
                            Iniciar sesion
                        </a>
                        <a
                            class="rounded-md bg-emerald-600 px-3 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700"
                            href="{{ route('register') }}"
                        >
                            Registrarse
                        </a>
                    @endauth
                    @yield('actions')
                </div>
            </header>

            {{-- ======= Page title ======= --}}
            @hasSection('page_title')
                <div class="mt-6">
                    <h1 class="text-3xl font-semibold text-stone-900">@yield('page_title')</h1>
                    @hasSection('page_subtitle')
                        <p class="mt-1 text-sm text-stone-500">@yield('page_subtitle')</p>
                    @endif
                </div>
            @endif

            {{-- ======= Flash messages ======= --}}
            @if (session('status'))
                <div class="mt-6 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-900">
                    {{ session('status') }}
                </div>
            @endif

            {{-- ======= Contenido principal ======= --}}
            <main class="mt-8 flex-1">
                @yield('content')
            </main>

            {{-- ======= Footer ======= --}}
            <footer class="mt-12 border-t border-stone-200 py-6 text-center text-xs text-stone-400">
                &copy; {{ date('Y') }} ReClóset — Todos los derechos reservados.
            </footer>
        </div>
    </body>
</html>
