<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Announcements' }} — {{ config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-blue-900/60 via-indigo-900/40 to-purple-800/60 text-white p-3 sm:p-0">

    {{-- Top Navigation Bar --}}
    {{-- <nav id="publicNav" class="bg-black/40 backdrop-blur-lg border-b border-white/10 sticky top-0 z-50 relative"> --}}
    <nav id="publicNav" class="bg-white backdrop-blur-lg border-b border-white/10 sticky top-0 z-50 relative">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">

                {{-- Brand --}}
                <a href="{{ route('announcements.index') }}" class="flex items-center gap-3 group">
                    <x-application-logo class="h-8 w-auto fill-current text-black" style="width:2rem;" />
                    {{-- <span class="font-bold text-base sm:text-lg tracking-wide group-hover:text-blue-300 transition-colors truncate max-w-[180px] sm:max-w-none">
                        {{ config('app.name') }}
                    </span> --}}
                </a>

                {{-- Right-side actions (desktop) --}}
                <div class="hidden sm:flex items-center gap-4">
                    {{-- <a href="{{ route('announcements.index') }}"
                       class="text-sm text-white/70 hover:text-white transition-colors {{ request()->routeIs('announcements.index') ? 'text-white font-semibold' : '' }}">
                        📢 Announcements
                    </a> --}}

                    @auth
                        <a href="{{ route('dashboard') }}"
                           class="text-sm text-black hover:text-black transition-colors">
                            Dashboard
                        </a>
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('announcements.manage') }}"
                               class="text-sm px-4 py-1.5 bg-blue-600 hover:bg-blue-500 rounded-lg font-semibold transition-colors">
                                Manage
                            </a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit"
                                    class="text-sm text-black hover:text-black transition-colors">
                                Log Out
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}"
                           class="text-sm text-white/70 hover:text-white transition-colors">
                            Log In
                        </a>
                        <a href="{{ route('register') }}"
                           class="text-sm px-4 py-1.5 bg-blue-600 hover:bg-blue-500 rounded-lg font-semibold transition-colors">
                            Register
                        </a>
                    @endauth
                </div>

                {{-- Hamburger (mobile) --}}
                <div class="flex items-center sm:hidden">
                    <button type="button" id="publicNavToggle" aria-controls="publicNavMenu" aria-expanded="false"
                            class="inline-flex items-center justify-center p-2 rounded-md text-white/70 hover:text-white hover:bg-white/10 focus:outline-none focus:bg-white/10 transition">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path data-icon="bars" class="inline-flex"
                                  stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 6h16M4 12h16M4 18h16" />
                            <path data-icon="close" class="hidden"
                                  stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        {{-- Mobile dropdown menu — absolute overlay so it doesn't push page content --}}
        <div id="publicNavMenu" hidden
             class=" absolute w-[80%] top-0 right-0 z-40 bg-black/90 backdrop-blur-lg border-t border-white/10 shadow-xl transition-all duration-200 opacity-0 -translate-y-2 sm:hidden">
            <div class="px-4 py-3 space-y-2">
                {{-- <a href="{{ route('announcements.index') }}"
                   class="block py-2 text-sm text-white/80 hover:text-white transition-colors {{ request()->routeIs('announcements.index') ? 'text-white font-semibold' : '' }}">
                    📢 Announcements
                </a> --}}

                @auth
                    {{-- <a href="{{ route('dashboard') }}"
                       class="block py-2 text-sm text-white/80 hover:text-white transition-colors">
                        Dashboard
                    </a> --}}
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('announcements.manage') }}"
                           class="block py-2 text-sm px-4 bg-blue-600 hover:bg-blue-500 rounded-lg font-semibold transition-colors text-white text-center">
                            Manage
                        </a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="block w-full text-left py-2 text-sm text-white/60 hover:text-white transition-colors">
                            Log Out
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                       class="block py-2 text-sm text-white/80 hover:text-white transition-colors">
                        Log In
                    </a>
                    <a href="{{ route('register') }}"
                       class="block py-2 text-sm px-4 bg-blue-600 hover:bg-blue-500 rounded-lg font-semibold transition-colors text-white text-center">
                        Register
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- Page Content --}}
    <main class="">
        {{ $slot }}
    </main>

    {{-- Minimal footer --}}
    <footer class="mt-16 border-t border-white/10 py-6 text-center text-sm bg-gradient-to-br from-blue-900/60 via-indigo-900/40 to-purple-800/60">
        &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
    </footer>

    <script>
        (function () {
            const toggle = document.getElementById('publicNavToggle');
            const menu   = document.getElementById('publicNavMenu');
            if (!toggle || !menu) return;

            const bars   = toggle.querySelector('[data-icon="bars"]');
            const close  = toggle.querySelector('[data-icon="close"]');

            const setOpen = (open) => {
                toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
                if (open) {
                    menu.hidden = false;
                    requestAnimationFrame(() => {
                        menu.classList.remove('opacity-0', '-translate-y-2');
                        menu.classList.add('opacity-100', 'translate-y-0');
                    });
                    bars && bars.classList.add('hidden');
                    bars && bars.classList.remove('inline-flex');
                    close && close.classList.remove('hidden');
                    close && close.classList.add('inline-flex');
                } else {
                    menu.classList.add('opacity-0', '-translate-y-2');
                    menu.classList.remove('opacity-100', 'translate-y-0');
                    setTimeout(() => { menu.hidden = true; }, 200);
                    bars && bars.classList.remove('hidden');
                    bars && bars.classList.add('inline-flex');
                    close && close.classList.add('hidden');
                    close && close.classList.remove('inline-flex');
                }
            };

            toggle.addEventListener('click', (e) => {
                e.stopPropagation();
                setOpen(toggle.getAttribute('aria-expanded') !== 'true');
            });

            document.addEventListener('click', (e) => {
                if (toggle.getAttribute('aria-expanded') !== 'true') return;
                if (menu.contains(e.target) || toggle.contains(e.target)) return;
                setOpen(false);
            });

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') setOpen(false);
            });
        })();
    </script>
</body>
</html>
