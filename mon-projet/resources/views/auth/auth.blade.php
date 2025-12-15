<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion - Iroise</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f7fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        form {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            width: 320px;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
        }

        button {
            margin-top: 20px;
            width: 100%;
            background-color: #2563eb;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 6px;
            cursor: pointer;
        }

        button:hover {
            background-color: #1d4ed8;
        }

        .error {
            color: red;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <form method="POST" action="{{ route('login.post') }}">
        @csrf
        <h2>Connexion Admin</h2>

        <input type="email" name="email" placeholder="Adresse e-mail" required>
        <input type="password" name="password" placeholder="Mot de passe" required>

        @error('email')
            <p class="error">{{ $message }}</p>
        @enderror

        <button type="submit">Se connecter</button>
    </form>
</body>
</html>
