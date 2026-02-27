<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iroise Association</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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

        /* En-tête avec glassmorphism */
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
        }

        .btn-login {
            background: rgba(255, 255, 255, 0.95);
            color: #1f2937;
            border: 2px solid transparent;
        }

        .btn-login:hover {
            background: white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            transform: translateY(-2px);
        }

        .btn-register {
            background: rgba(230, 230, 235, 0.95);
            color: #1f2937;
            border: 2px solid transparent;
        }

        .btn-register:hover {
            background: white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            transform: translateY(-2px);
        }

        /* Contenu principal avec glassmorphism */
        .main-welcome {
            min-height: calc(100vh - 80px);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 40px 20px;
        }

        .welcome-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(8px);
            border-radius: 20px;
            padding: 60px 80px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.3);
            max-width: 600px;
        }

        .welcome-card h2 {
            font-size: 2.5rem;
            color: #1f2937;
            margin-bottom: 20px;
            font-weight: 700;
        }

        .welcome-card p {
            color: #374151;
            font-size: 1.2rem;
            margin-bottom: 40px;
            font-weight: 400;
        }

        .btn-container {
            display: flex;
            gap: 20px;
            justify-content: center;
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

        .welcome-card {
            animation: fadeIn 0.6s ease;
        }
    </style>
</head>
<body class="bg-hero">
    <!-- En-tête -->
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

    <!-- Contenu principal -->
    <main class="main-welcome">
        <div class="welcome-card">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-300 text-green-800 rounded">
                    {{ session('success') }}
                    <div class="mt-2">
                        <a href="{{ route('dashboard') }}" class="text-blue-600 underline">Aller au tableau de bord</a>
                    </div>
                </div>
            @endif
            <h2>Bienvenue</h2>
            <p>Connectez-vous ou inscrivez-vous pour accéder à votre espace personnel.</p>
            
            <div class="btn-container">
                <a href="{{ route('login') }}" class="btn btn-login">Connexion</a>
                <span class="btn btn-register" style="cursor:default">Pour vous inscrire, contactez l'association</span>
            </div>
        </div>
    </main>
</body>
</html>
