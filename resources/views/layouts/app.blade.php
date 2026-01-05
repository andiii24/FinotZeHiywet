<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'ፍኖት ዘሕይወት') }} - መንፈሳዊ ማህበር</title>

        <!-- Favicon -->
        <link rel="icon" type="image/jpeg" href="{{ asset('images/logo.jpg') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @if(file_exists(public_path('build/manifest.json')))
            @php
                $manifest = json_decode(file_get_contents(public_path('build/manifest.json')), true);
            @endphp
            <link rel="stylesheet" href="{{ asset('build/' . $manifest['resources/css/app.css']['file']) }}">
            <script type="module" src="{{ asset('build/' . $manifest['resources/js/app.js']['file']) }}"></script>
        @else
                    @if(file_exists(public_path('build/manifest.json')))
            @php
                $manifest = json_decode(file_get_contents(public_path('build/manifest.json')), true);
            @endphp
            <link rel="stylesheet" href="{{ asset('build/' . $manifest['resources/css/app.css']['file']) }}">
            <script type="module" src="{{ asset('build/' . $manifest['resources/js/app.js']['file']) }}"></script>
        @else
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
        @endif

        <!-- SweetAlert2 CDN -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
            <!-- Sidebar -->
            @auth
                @include('layouts.sidebar')
            @endauth

            <!-- Main Content -->
            <div class="@auth lg:ml-64 transition-all duration-300 @endauth">
                @auth
                    <!-- Mobile menu button -->
                    <div class="lg:hidden fixed top-0 right-0 p-4 z-20">
                        <button id="mobile-menu-button" class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white p-2 rounded-md shadow-lg hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-300">
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>
                @else
                    @include('layouts.navigation')
                @endauth

                <!-- Page Heading -->
                @isset($header)
                    <header class="bg-white shadow">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <!-- Page Content -->
                <main class="py-4">
                    @hasSection('content')
                        @yield('content')
                    @else
                        {{ $slot ?? '' }}
                    @endif
                </main>
            </div>
        </div>

        <script>
            // Mobile menu toggle
            document.addEventListener('DOMContentLoaded', function() {
                const mobileMenuButton = document.getElementById('mobile-menu-button');
                const sidebar = document.querySelector('.sidebar');

                if (!sidebar) return; // Exit if sidebar doesn't exist

                // Initialize sidebar state for mobile only
                if (window.innerWidth < 1024) { // lg breakpoint
                    sidebar.classList.add('-translate-x-full');
                }

                if (mobileMenuButton && sidebar) {
                    mobileMenuButton.addEventListener('click', function() {
                        if (sidebar.classList.contains('-translate-x-full')) {
                            // Open sidebar
                            sidebar.classList.remove('-translate-x-full');

                            // Add overlay
                            const overlay = document.createElement('div');
                            overlay.id = 'sidebar-overlay';
                            overlay.className = 'fixed inset-0 bg-black bg-opacity-50 z-0 lg:hidden';
                            document.body.appendChild(overlay);

                            overlay.addEventListener('click', function() {
                                sidebar.classList.add('-translate-x-full');
                                overlay.remove();
                            });
                        } else {
                            // Close sidebar
                            sidebar.classList.add('-translate-x-full');

                            // Remove overlay
                            const overlay = document.getElementById('sidebar-overlay');
                            if (overlay) overlay.remove();
                        }
                    });
                }

                // Add animation effects to cards
                const cards = document.querySelectorAll('.hover\\:shadow-lg');
                cards.forEach(card => {
                    card.addEventListener('mouseenter', function() {
                        this.classList.add('scale-105');
                        this.style.transition = 'all 0.3s ease';
                    });
                    card.addEventListener('mouseleave', function() {
                        this.classList.remove('scale-105');
                    });
                });

                // Handle window resize
                window.addEventListener('resize', function() {
                    if (!sidebar) return;

                    if (window.innerWidth >= 1024) { // lg breakpoint
                        sidebar.classList.remove('-translate-x-full');
                        const overlay = document.getElementById('sidebar-overlay');
                        if (overlay) overlay.remove();
                    } else {
                        if (!sidebar.classList.contains('-translate-x-full')) {
                            // If sidebar is open on mobile and screen becomes larger, add overlay
                            if (!document.getElementById('sidebar-overlay')) {
                                const overlay = document.createElement('div');
                                overlay.id = 'sidebar-overlay';
                                overlay.className = 'fixed inset-0 bg-black bg-opacity-50 z-0 lg:hidden';
                                document.body.appendChild(overlay);

                                overlay.addEventListener('click', function() {
                                    sidebar.classList.add('-translate-x-full');
                                    overlay.remove();
                                });
                            }
                        }
                    }
                });
            });
        </script>
    </body>
</html>
