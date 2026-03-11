<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('styles')
    </head>
    <body class="font-sans antialiased">
        <div
            class="min-h-screen bg-hero"
            style="background-image: linear-gradient(rgba(10, 15, 25, 0.35), rgba(10, 15, 25, 0.35)), url('{{ asset('images/ias-lampaul-2.jpg') }}');"
        >
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="header-glass dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="px-4 sm:px-6 lg:px-8 py-8">
                <div class="card-glass max-w-7xl mx-auto p-6">
                    @yield('content')
                    {{ $slot ?? '' }}
                </div>
            </main>
        </div>
        <!-- Global footer -->
        <footer class="bg-gray-100 dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700 mt-8">
            <div class="max-w-7xl mx-auto px-4 py-6 flex flex-col sm:flex-row justify-between items-center text-sm text-gray-700 dark:text-gray-300">
                <div class="mb-4 sm:mb-0 text-center sm:text-left">
                    <strong>Association Iroise</strong><br>
                    12 rue de la Plage, 29430 Lampaul-Plouarzel<br>
                    Tél. : 02 98 00 00 00<br>
                    Email: <a href="mailto:contact@association-iroise.org" class="text-blue-600 underline">contact@association-iroise.org</a>
                </div>
                <div class="text-sm">
                    <a href="{{ route('home') }}" class="mr-4 hover:underline">Accueil</a>
                    <a href="#contact" class="hover:underline">Nous contacter</a>
                </div>
            </div>
        </footer>

        @stack('scripts')
    </body>
</html>
