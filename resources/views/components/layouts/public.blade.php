<!DOCTYPE html>
<html lang="ms">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        @php
            $pageTitle = $title ?? config('app.name', 'CariSnap');
            $desc = $metaDescription ?? 'Cari dan tempah jurugambar atau juruvideo perkahwinan terbaik di Malaysia. Tapis ikut lokasi, bajet, dan tarikh kekosongan.';
            $image = $ogImage ?? asset('images/og-default.svg');
            $noIndex = $noIndex ?? false;
            $canonical = $canonical ?? url()->current();
            $ogType = $ogType ?? 'website';
        @endphp
        
        <title>{{ $pageTitle }}</title>
        <meta name="description" content="{{ $desc }}">
        @if ($noIndex)
            <meta name="robots" content="noindex, nofollow">
        @endif
        <link rel="canonical" href="{{ $canonical }}">

        <x-favicon />
        
        <!-- OpenGraph Meta Tags -->
        <meta property="og:site_name" content="CariSnap">
        <meta property="og:locale" content="ms_MY">
        <meta property="og:title" content="{{ $pageTitle }}">
        <meta property="og:description" content="{{ $desc }}">
        <meta property="og:type" content="{{ $ogType }}">
        <meta property="og:url" content="{{ $canonical }}">
        <meta property="og:image" content="{{ $image }}">
        
        <!-- Twitter Card -->
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="{{ $pageTitle }}">
        <meta name="twitter:description" content="{{ $desc }}">
        <meta name="twitter:image" content="{{ $image }}">
        
        @stack('meta')
        
        <!-- Scripts and Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
        @stack('styles')
        @stack('head-scripts')
    </head>
    <body class="font-sans antialiased bg-gray-50 text-gray-900 w-full overflow-x-hidden min-h-screen flex flex-col">
        <!-- Header -->
        <header
            x-data="{ open: false }"
            class="sticky top-0 z-50 bg-white/80 backdrop-blur-md border-b border-gray-100"
        >
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <!-- Logo -->
                    <div class="flex-shrink-0 flex items-center">
                        <a href="{{ route('home') }}" wire:navigate class="hover:opacity-90 transition-opacity" aria-label="CariSnap — laman utama">
                            <x-ui.logo />
                        </a>
                    </div>
                    
                    <!-- Mobile Menu Button -->
                    <div class="flex items-center sm:hidden">
                        <button
                            type="button"
                            @click="open = !open"
                            class="text-gray-500 hover:text-gray-900 focus:outline-none p-2"
                            :aria-expanded="open"
                            aria-label="Toggle menu"
                        >
                            <svg x-show="!open" class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                            <svg x-show="open" x-cloak class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    
                    <!-- Desktop Nav -->
                    <nav class="hidden sm:flex items-center gap-1">
                        <a href="{{ route('photographers.index') }}" wire:navigate class="text-gray-600 hover:text-brand-600 px-3 py-2 text-sm font-medium transition-colors">
                            Cari Jurugambar
                        </a>
                        @auth
                            @if (auth()->user()->role === \App\Enums\UserRole::Photographer)
                                <a href="/photographer" class="text-gray-600 hover:text-brand-600 px-3 py-2 text-sm font-medium transition-colors">
                                    Panel Pro
                                </a>
                            @elseif (auth()->user()->role === \App\Enums\UserRole::Admin)
                                <a href="/admin" class="text-gray-600 hover:text-brand-600 px-3 py-2 text-sm font-medium transition-colors">
                                    Admin
                                </a>
                            @elseif (auth()->user()->hasVerifiedEmail())
                                <a href="{{ route('bookings.index') }}" wire:navigate class="text-gray-600 hover:text-brand-600 px-3 py-2 text-sm font-medium transition-colors">
                                    Tempahan Saya
                                </a>
                            @endif
                        @else
                            <a href="{{ route('login') }}" wire:navigate class="text-gray-600 hover:text-brand-600 px-3 py-2 text-sm font-medium transition-colors">
                                Log Masuk
                            </a>
                            <a href="{{ route('register.photographer') }}" wire:navigate class="bg-brand-600 text-white hover:bg-brand-700 px-4 py-2 rounded-full text-sm font-medium transition-colors ml-2">
                                Daftar sebagai Pro
                            </a>
                        @endauth
                    </nav>
                </div>
            </div>

            <!-- Mobile Nav -->
            <div
                x-show="open"
                x-cloak
                x-transition:enter="transition ease-out duration-150"
                x-transition:enter-start="opacity-0 -translate-y-1"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-100"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 -translate-y-1"
                class="sm:hidden border-t border-gray-100 bg-white"
            >
                <nav class="max-w-7xl mx-auto px-4 py-3 flex flex-col gap-1">
                    <a href="{{ route('photographers.index') }}" wire:navigate @click="open = false" class="text-gray-700 hover:text-brand-600 px-3 py-2.5 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                        Cari Jurugambar
                    </a>
                    @auth
                        @if (auth()->user()->role === \App\Enums\UserRole::Photographer)
                            <a href="/photographer" @click="open = false" class="text-gray-700 hover:text-brand-600 px-3 py-2.5 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                                Panel Pro
                            </a>
                        @elseif (auth()->user()->role === \App\Enums\UserRole::Admin)
                            <a href="/admin" @click="open = false" class="text-gray-700 hover:text-brand-600 px-3 py-2.5 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                                Admin
                            </a>
                        @elseif (auth()->user()->hasVerifiedEmail())
                            <a href="{{ route('bookings.index') }}" wire:navigate @click="open = false" class="text-gray-700 hover:text-brand-600 px-3 py-2.5 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                                Tempahan Saya
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" wire:navigate @click="open = false" class="text-gray-700 hover:text-brand-600 px-3 py-2.5 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                            Log Masuk
                        </a>
                        <a href="{{ route('register.photographer') }}" wire:navigate @click="open = false" class="bg-brand-600 text-white hover:bg-brand-700 px-4 py-2.5 rounded-full text-sm font-medium transition-colors text-center mt-1">
                            Daftar sebagai Pro
                        </a>
                    @endauth
                </nav>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-grow w-full max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
            {{ $slot }}
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-100 mt-auto">
            <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
                <nav class="flex flex-wrap justify-center gap-x-6 gap-y-2 text-sm text-gray-500 mb-4">
                    <a href="{{ route('privacy') }}" wire:navigate class="hover:text-brand-600 transition-colors">Dasar Privasi</a>
                    <a href="{{ route('terms') }}" wire:navigate class="hover:text-brand-600 transition-colors">Terma Penggunaan</a>
                    <a href="mailto:hello@carisnap.my" class="hover:text-brand-600 transition-colors">hello@carisnap.my</a>
                </nav>
                <p class="text-center text-sm text-gray-500 font-medium">
                    &copy; {{ date('Y') }} CariSnap. Hak cipta terpelihara.
                </p>
            </div>
        </footer>

        @livewireScripts
        @stack('scripts')
    </body>
</html>
