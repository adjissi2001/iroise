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

    <body class="font-sans antialiased" style="display:flex; flex-direction:column; min-height:100vh; margin:0;">
        <div style="flex:1; position:relative;">
            {{-- Background image flou --}}
            <div style="position:fixed; inset:0; z-index:-1; background-image:url('{{ asset('images/ias-lampaul-2.jpg') }}'); background-size:cover; background-position:center; background-repeat:no-repeat; filter:blur(4px); transform:scale(1.05);"></div>
            <div style="position:fixed; inset:0; z-index:-1; background:rgba(255,255,255,0.3);"></div>


            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header style="background:rgba(255,255,255,0.75); backdrop-filter:blur(6px); -webkit-backdrop-filter:blur(6px); box-shadow:0 8px 24px rgba(0,0,0,0.08);">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="px-4 sm:px-6 lg:px-8 py-8">
                <div style="max-width:1280px; margin:0 auto; padding:1.5rem; background:rgba(255,255,255,0.75); backdrop-filter:blur(8px); -webkit-backdrop-filter:blur(8px); border-radius:18px; box-shadow:0 12px 32px rgba(0,0,0,0.12);">
                    @yield('content')
                    {{ $slot ?? '' }}
                </div>
            </main>
        </div>
        <!-- Global footer -->
        <footer style="background:linear-gradient(135deg,#1e3a5f 0%,#2563eb 100%); margin-top:auto; padding:1.5rem 1.5rem;">
            <div style="display:flex; flex-direction:column; align-items:center; gap:0.6rem; font-size:0.85rem; color:rgba(255,255,255,0.85); text-align:center;">
                <div style="display:flex; align-items:center; gap:0.4rem;">
                    <strong style="color:#fff;">Iroise Actions Solidaires</strong>
                    <span style="color:rgba(255,255,255,0.4);">|</span>
                    <a href="mailto:iroisesaintrenan@gmail.com" style="color:rgba(255,255,255,0.9); text-decoration:none;">iroisesaintrenan@gmail.com</a>
                </div>
                <div style="display:flex; flex-wrap:wrap; justify-content:center; gap:1.5rem;">
                    <span>Secteur Saint-Renan Lanrivoaré : <strong style="color:#fff;">07 82 33 53 12</strong></span>
                    <span>Secteur Lampaul Plouarzel Ploumoguer : <strong style="color:#fff;">07 67 56 47 26</strong></span>
                </div>
            </div>
        </footer>

        @stack('scripts')
    </body>
</html>
