@php
    /** @var string $tempPassword */
    /** @var string $activationUrl */
    /** @var string $loginUrl */
@endphp

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Activation de votre compte</title>
</head>
<body style="margin:0;padding:0;background:#f5f7fb;font-family:Arial, Helvetica, sans-serif;color:#111827;">
    <div style="max-width:640px;margin:0 auto;padding:24px;">
        <div style="background:#ffffff;border:1px solid rgba(15,23,42,0.08);border-radius:14px;padding:22px;">
            <h1 style="margin:0 0 10px 0;font-size:20px;">Votre compte a été créé</h1>

            <p style="margin:0 0 12px 0;line-height:1.5;">
                Bonjour,<br>
                Un compte vient d’être créé pour vous sur le site de l’association.
            </p>

            <p style="margin:0 0 10px 0;line-height:1.5;">
                <strong>Mot de passe temporaire</strong> :
                <span style="font-family:Consolas, Menlo, Monaco, monospace;background:#f3f4f6;padding:2px 6px;border-radius:6px;">{{ $tempPassword }}</span>
            </p>

            <p style="margin:0 0 14px 0;line-height:1.5;">
                <strong>Important :</strong> pour des raisons de sécurité, nous vous recommandons de <strong>commencer par activer votre compte</strong>
                en choisissant votre propre mot de passe via le lien ci-dessous. Une fois votre mot de passe changé,
                le mot de passe temporaire ci-dessus ne sera plus utilisable.
            </p>

            <p style="margin:0 0 14px 0;line-height:1.5;">
                Pour <strong>activer votre compte</strong>, choisissez un nouveau mot de passe en cliquant ici :
            </p>

            <p style="margin:0 0 18px 0;">
                <a href="{{ $activationUrl }}" style="display:inline-block;background:#0b63ff;color:#ffffff;text-decoration:none;padding:10px 14px;border-radius:10px;font-weight:700;">
                    Activer mon compte (changer mon mot de passe)
                </a>
            </p>

            <p style="margin:0 0 10px 0;line-height:1.5;">
                Ensuite, vous pourrez vous connecter ici (avec votre email + le nouveau mot de passe que vous venez de choisir) :
                <a href="{{ $loginUrl }}">{{ $loginUrl }}</a>
            </p>

            <p style="margin:14px 0 0 0;font-size:12px;color:#6b7280;line-height:1.5;">
                Si vous n’êtes pas à l’origine de cette demande, vous pouvez ignorer cet email.
            </p>
        </div>
    </div>
</body>
</html>
