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
        <style>
            /* Background image */
            body {
                background-image: linear-gradient(rgba(10, 15, 25, 0.35), rgba(10, 15, 25, 0.35)), url('/images/ias-lampaul-2.jpg');
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;
                background-attachment: fixed;
            }

            /* Style moderne et clair pour les formulaires */
            .card-glass {
                background: rgba(255, 255, 255, 0.95) !important;
                backdrop-filter: blur(15px) !important;
            }
            
            .auth-form label {
                color: #1f2937 !important;
                font-weight: 700 !important;
                font-size: 0.95rem !important;
                margin-bottom: 0.5rem;
                display: block;
            }
            
            .auth-form input[type="text"],
            .auth-form input[type="email"],
            .auth-form input[type="password"] {
                background: white !important;
                border: 2px solid #e5e7eb !important;
                color: #111827 !important;
                font-size: 1rem !important;
                font-weight: 500 !important;
                padding: 0.875rem !important;
                border-radius: 0.5rem !important;
                transition: all 0.3s ease !important;
                width: 100%;
            }
            
            .auth-form input[type="text"]:focus,
            .auth-form input[type="email"]:focus,
            .auth-form input[type="password"]:focus {
                border-color: #3b82f6 !important;
                background: white !important;
                box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
                outline: none !important;
            }
            
            .auth-form input[type="checkbox"] {
                width: 1.1rem !important;
                height: 1.1rem !important;
                border: 2px solid #d1d5db !important;
                border-radius: 0.25rem !important;
                background: white !important;
            }
            
            .auth-form a {
                color: #3b82f6 !important;
                font-weight: 600 !important;
                text-decoration: none !important;
                transition: all 0.3s ease !important;
            }
            
            .auth-form a:hover {
                color: #2563eb !important;
                text-decoration: underline !important;
            }
            
            .auth-form button[type="submit"] {
                background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%) !important;
                color: white !important;
                font-weight: 700 !important;
                padding: 0.875rem 2rem !important;
                border-radius: 0.5rem !important;
                border: none !important;
                font-size: 0.95rem !important;
                text-transform: uppercase !important;
                letter-spacing: 0.05em !important;
                transition: all 0.3s ease !important;
                box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3) !important;
                cursor: pointer;
            }
            
            .auth-form button[type="submit"]:hover {
                background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%) !important;
                transform: translateY(-2px) !important;
                box-shadow: 0 6px 16px rgba(59, 130, 246, 0.4) !important;
            }
            
            .auth-form .remember-label {
                color: #374151 !important;
                font-weight: 500 !important;
                font-size: 0.9rem;
            }
            
            .auth-title {
                color: #1f2937 !important;
                font-size: 1.5rem !important;
                font-weight: 700 !important;
                margin-bottom: 1.5rem !important;
                text-align: center !important;
            }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased bg-hero">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <div class="mb-6">
                <a href="/" class="inline-block">
                    <div class="text-center">
                        <h1 class="text-3xl font-bold text-black mb-2" style="text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);">Iroise Association</h1>
                        <p class="text-black text-sm" style="text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.5);">Espace de connexion</p>
                    </div>
                </a>
            </div>

            <div class="w-full sm:max-w-md px-6 py-8 card-glass shadow-lg overflow-hidden sm:rounded-lg auth-form">
                {{ $slot }}
            </div>
            
            <div class="mt-6 text-center">
                <a href="/" class="text-black hover:text-gray-200 transition-colors duration-300" style="text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.5);">
                    ← Retour à l'accueil
                </a>
            </div>
        </div>
        @stack('scripts')
    </body>
</html>
