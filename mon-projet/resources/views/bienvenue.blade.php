

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
   <style>
    /* 🌈 Style global */
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;

        /* 👉 Ton background image */
        background-image: url('/images/ias-lampaul-2.jpg');
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center;
        background-attachment: fixed;

        /* 👉 Style déjà présent */
        margin: 0;
        padding: 0;
        height: 100vh;
        display: flex;
        flex-direction: column;
    }

    /* 🌟 En-tête */
    header {
        background:#CAE4DB;
        color: white;
        padding: 15px 40px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        backdrop-filter: blur(4px);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    header h1 {
        margin: 0;
        font-size: 22px;
        letter-spacing: 1px;
    }

    header nav a {
        color: white;
        text-decoration: none;
        margin-left: 20px;
        font-weight: 600;
        transition: color 0.3s;
    }

    header nav a:hover {
        color: #dceeff;
    }

    /* 🧩 Section principale */
    main {
      
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        padding: 40px;
        background: rgba(255, 255, 255, 0.55);
        border-radius: 20px;
        backdrop-filter: blur(6px);
        width: 20%;
        margin: auto;
    }

    main p {
        color: #333;
        font-size: 1.1rem;
        margin-bottom: 30px;
        font-weight: 250;
    }

    /* 🔘 Boutons */
    .btn-container {
        display: flex;
        gap: 20px;
    }

    .btn {
        padding: 12px 28px;
        font-size: 16px;
        border-radius: 10px;
        cursor: pointer;
        font-weight: 600;
        transition: 0.3s;
        text-decoration: none;
    }

    .btn-login {
        background: #fbfbffff;
        color: black;
    }

    .btn-login:hover {
            background-color: #ffffff;
 
   }

    .btn-register {
        background: #e6e6ebff;
        color: black;
    }

    .btn-register:hover {
        background-color: #ffffff;
        color: black;
    }


    #login-form form {
    max-width: 100%;
    box-sizing: border-box; 
    }

    #login-form input,
    #login-form button {
        width: 100%;        
        box-sizing: border-box;
    }

    
    #login-form form {
    background: rgba(255, 255, 255, 0.65);
    padding: 30px;
    border-radius: 15px;
    backdrop-filter: blur(6px);
    box-shadow: 0 5px 20px rgba(0,0,0,0.2);
    width: 100%;
    display: flex;
    flex-direction: column;
    gap: 15px;
    animation: fadeIn 0.4s ease;
    }

    #login-form h2 {
        margin: 0;
        color: #333;
        font-weight: 600;
        font-size: 1.4rem;
    }

    #login-form input {
        width: 100%;
        padding: 12px;
        border: 1px solid #bbbbbb;
        border-radius: 8px;
        font-size: 15px;
        transition: 0.3s;
    }

    /* focus sur inputs */
    #login-form input:focus {
        border-color: #888892;
        box-shadow: 0 0 6px rgba(136, 136, 146, 0.3);
        outline: none;
    }

    #login-form button {
        background: #888892;
        color: white;
        padding: 12px;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        cursor: pointer;
        transition: 0.3s;
    }

    #login-form button:hover {
        background: white;
        color: black;
    }

    /* Animation d’apparition */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

</style>

</head>
<body>

    <!-- 🔹 En-tête -->
    <header>
        <h1 style = " color : black; ">Iroise Association</h1>
        <nav>
            @auth
                <a href="{{ route('dashboard') }}" class="btn btn-login">Tableau de bord</a>
            @else
                <a href="{{ route('login') }}" class="btn btn-login">Connexion</a>
                <a href="{{ route('register') }}" class="btn btn-register">Inscription</a>
            @endauth
        </nav>
    </header>

    <!-- 🔹 Contenu principal -->
    <main>
        <p>Connectez-vous ou inscrivez-vous pour accéder à votre espace personnel.</p>

        <div class="btn-container">
            <a href="{{ route('login') }}" class="btn btn-login">Connexion</a>
            <a href="{{ route('register') }}" class="btn btn-register">S'inscrire</a>
        </div>
    </main>

</body>
</html>
