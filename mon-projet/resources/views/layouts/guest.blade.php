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
            body {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                margin: 0;
                padding: 0;
                background-image: linear-gradient(rgba(10, 15, 25, 0.35), rgba(10, 15, 25, 0.35)), url('/images/ias-lampaul-2.jpg');
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;
                background-attachment: fixed;
                min-height: 100vh;
            }

            /* Header (comme accueil) */
            .header-welcome {
                background: rgba(150, 161, 158, 0.9);
                backdrop-filter: blur(10px);
                padding: 20px 40px;
                display: flex;
                justify-content: space-between;
                align-items: center;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            }

            .header-welcome h1 {
                margin: 0;
                font-size: 24px;
                letter-spacing: 1px;
                color: #1f2937;
                font-weight: 700;
            }

            .header-welcome nav {
                display: flex;
                gap: 15px;
            }

            .btn {
                padding: 12px 28px;
                font-size: 16px;
                border-radius: 10px;
                cursor: pointer;
                font-weight: 600;
                transition: all 0.3s;
                text-decoration: none;
                display: inline-block;
                border: 2px solid transparent;
            }

            .btn-login {
                background: rgba(255, 255, 255, 0.95);
                color: #1f2937;
            }

            .btn-login:hover {
                background: white;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
                transform: translateY(-2px);
            }

            .btn-register {
                background: rgba(230, 230, 235, 0.95);
                color: #1f2937;
            }

            .btn-register:hover {
                background: white;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
                transform: translateY(-2px);
            }

            /* Contenu principal */
            .main-welcome {
                min-height: calc(100vh - 80px);
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                text-align: center;
                padding: 40px 20px;
            }

            /* Carte glass (comme accueil) */
            .welcome-card {
                background: rgba(255, 255, 255, 0.9);
                backdrop-filter: blur(8px);
                border-radius: 20px;
                padding: 40px 50px;
                box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
                border: 1px solid rgba(255, 255, 255, 0.3);
                width: 100%;
                max-width: 900px;
                text-align: left;
                animation: fadeIn 0.6s ease;
            }

            @media (max-width: 640px) {
                .header-welcome {
                    padding: 16px 16px;
                }
                .welcome-card {
                    padding: 28px 20px;
                }
                .btn {
                    padding: 10px 16px;
                    font-size: 14px;
                }
            }

            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            /* Style moderne et clair pour les formulaires */
            .auth-form label {
                color: #1f2937 !important;
                font-weight: 700 !important;
                font-size: 0.95rem !important;
                margin-bottom: 0.5rem;
                display: block;
                text-align: left;
            }

            .auth-form input[type="text"],
            .auth-form input[type="email"],
            .auth-form input[type="password"],
            .auth-form input[type="date"],
            .auth-form select {
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
            .auth-form input[type="password"]:focus,
            .auth-form input[type="date"]:focus,
            .auth-form select:focus {
                border-color: #3b82f6 !important;
                background: white !important;
                box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
                outline: none !important;
            }

            .auth-title {
                color: #1f2937 !important;
                font-size: 2rem !important;
                font-weight: 700 !important;
                margin-bottom: 0.5rem !important;
                text-align: center !important;
            }

            .auth-subtitle {
                color: #374151;
                font-size: 1.05rem;
                margin-bottom: 1.75rem;
                text-align: center;
            }

            .form-grid {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 16px 20px;
            }

            @media (max-width: 768px) {
                .form-grid {
                    grid-template-columns: 1fr;
                }
            }

            .form-actions {
                display: flex;
                gap: 12px;
                justify-content: center;
                align-items: center;
                margin-top: 24px;
                flex-wrap: wrap;
            }
        </style>
    </head>
    <body class="bg-hero">
        <header class="header-welcome">
            <h1>Iroise Association</h1>
            <nav>
                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-login">Tableau de bord</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-login">Connexion</a>
                @endauth
            </nav>
        </header>

        <main class="main-welcome">
            <div class="welcome-card auth-form">
                {{ $slot }}
            </div>
        </main>
        @stack('scripts')
    </body>
</html>
