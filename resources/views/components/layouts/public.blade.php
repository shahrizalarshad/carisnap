<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        @php
            $pageTitle = $title ?? config('app.name', 'CariSnap');
            $desc = $metaDescription ?? 'Cari dan tempah jurugambar atau juruvideo perkahwinan terbaik di Malaysia. Tapis ikut lokasi, bajet, dan tarikh kekosongan.';
            $image = $ogImage ?? asset('images/og-default.jpg'); // Fallback if no specific OG image
        @endphp
        
        <title>{{ $pageTitle }}</title>
        <meta name="description" content="{{ $desc }}">
        
        <!-- OpenGraph Meta Tags -->
        <meta property="og:title" content="{{ $pageTitle }}">
        <meta property="og:description" content="{{ $desc }}">
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ request()->url() }}">
        <meta property="og:image" content="{{ $image }}">
        
        @stack('meta')
        
        <!-- Scripts and Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
        @stack('styles')
    </head>
    <body class="font-sans antialiased bg-gray-50 text-gray-900 w-full overflow-x-hidden min-h-screen flex flex-col">
        <!-- Header -->
        <header class="sticky top-0 z-50 bg-white/80 backdrop-blur-md border-b border-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <!-- Logo -->
                    <div class="flex-shrink-0 flex items-center">
                        <a href="/styleguide" wire:navigate class="font-heading font-bold text-2xl tracking-tight text-brand-600">
                            CariSnap.
                        </a>
                    </div>
                    
                    <!-- Mobile Menu Button -->
                    <div class="flex items-center sm:hidden">
                        <button type="button" class="text-gray-500 hover:text-gray-900 focus:outline-none p-2">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>
                    
                    <!-- Desktop Nav -->
                    <nav class="hidden sm:flex space-x-8">
                        <a href="/styleguide" wire:navigate class="text-gray-600 hover:text-brand-600 px-3 py-2 text-sm font-medium transition-colors">Styleguide</a>
                        <a href="#" class="text-gray-600 hover:text-brand-600 px-3 py-2 text-sm font-medium transition-colors">Log In</a>
                        <a href="#" class="bg-brand-600 text-white hover:bg-brand-700 px-4 py-2 rounded-full text-sm font-medium transition-colors">Join as Pro</a>
                    </nav>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-grow w-full max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
            {{ $slot }}
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-100 mt-auto">
            <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
                <p class="text-center text-sm text-gray-500 font-medium">
                    &copy; {{ date('Y') }} CariSnap. Hak cipta terpelihara.
                </p>
            </div>
        </footer>

        @livewireScripts
        @stack('scripts')
    </body>
</html>
