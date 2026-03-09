<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Activation de compte</title>
</head>
<body style="font-family: Arial, Helvetica, sans-serif; line-height: 1.5; color: #111827;">
    <p>Bonjour,</p>

    <p>Un compte a été créé pour vous sur le site de l'association.</p>

    <p>
        <strong>Identifiant :</strong> {{ $user->email }}<br>
        <strong>Mot de passe temporaire :</strong> {{ $temporaryPassword }}
    </p>

    <p>
        Pour <strong>activer votre compte</strong>, merci de choisir un nouveau mot de passe via ce lien :<br>
        <a href="{{ $activationUrl }}" target="_blank" rel="noopener">Activer mon compte / Changer mon mot de passe</a>
    </p>

    @if(!empty($siteUrl))
        <p>
            Accéder au site : <a href="{{ $siteUrl }}" target="_blank" rel="noopener">{{ $siteUrl }}</a>
        </p>
    @endif

    <p>
        Si vous n'êtes pas à l'origine de cette demande, vous pouvez ignorer cet email.
    </p>

    <p>Cordialement,<br>L'équipe</p>
</body>
</html>
