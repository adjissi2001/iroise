<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Retrait de mission</title>
</head>
<body style="margin:0; padding:0; background:#f3f4f6; font-family:Arial, sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background:#f3f4f6; padding:40px 0;">
        <tr>
            <td align="center">
                <table width="560" cellpadding="0" cellspacing="0" style="background:#ffffff; border-radius:12px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,0.08);">
                    {{-- En-tête --}}
                    <tr>
                        <td style="background:linear-gradient(135deg,#dc2626,#b91c1c); padding:24px 32px; text-align:center;">
                            <h1 style="margin:0; color:#ffffff; font-size:20px; font-weight:700;">Retrait d'une mission</h1>
                        </td>
                    </tr>

                    {{-- Contenu --}}
                    <tr>
                        <td style="padding:28px 32px;">
                            <p style="margin:0 0 16px; font-size:15px; color:#374151; line-height:1.6;">
                                Bonjour,
                            </p>
                            <p style="margin:0 0 20px; font-size:15px; color:#374151; line-height:1.6;">
                                Le bénévole <strong style="color:#0f172a;">{{ $benevole->prenom }} {{ $benevole->nom }}</strong> vient de se retirer de la mission suivante :
                            </p>

                            {{-- Détails mission --}}
                            <table width="100%" cellpadding="0" cellspacing="0" style="background:#f9fafb; border:1px solid #e5e7eb; border-radius:8px; margin-bottom:20px;">
                                <tr>
                                    <td style="padding:16px 20px;">
                                        <table width="100%" cellpadding="0" cellspacing="0">
                                            @if($mission->nom_lieu)
                                            <tr>
                                                <td style="padding:4px 0; font-size:14px; color:#6b7280; width:130px; vertical-align:top;">Lieu :</td>
                                                <td style="padding:4px 0; font-size:14px; color:#111827; font-weight:600;">{{ $mission->nom_lieu }}</td>
                                            </tr>
                                            @endif
                                            @if($mission->commune)
                                            <tr>
                                                <td style="padding:4px 0; font-size:14px; color:#6b7280; width:130px; vertical-align:top;">Commune :</td>
                                                <td style="padding:4px 0; font-size:14px; color:#111827; font-weight:600;">{{ $mission->commune }}</td>
                                            </tr>
                                            @endif
                                            <tr>
                                                <td style="padding:4px 0; font-size:14px; color:#6b7280; width:130px; vertical-align:top;">Date :</td>
                                                <td style="padding:4px 0; font-size:14px; color:#111827; font-weight:600;">{{ \Carbon\Carbon::parse($mission->date_depart)->translatedFormat('l d F Y') }}</td>
                                            </tr>
                                            @if($mission->heure_depart)
                                            <tr>
                                                <td style="padding:4px 0; font-size:14px; color:#6b7280; width:130px; vertical-align:top;">Heure départ :</td>
                                                <td style="padding:4px 0; font-size:14px; color:#111827; font-weight:600;">{{ $mission->heure_depart }}</td>
                                            </tr>
                                            @endif
                                            @if($mission->heure_arrivee)
                                            <tr>
                                                <td style="padding:4px 0; font-size:14px; color:#6b7280; width:130px; vertical-align:top;">Heure arrivée :</td>
                                                <td style="padding:4px 0; font-size:14px; color:#111827; font-weight:600;">{{ $mission->heure_arrivee }}</td>
                                            </tr>
                                            @endif
                                            @if($mission->beneficiaires->count())
                                            <tr>
                                                <td style="padding:4px 0; font-size:14px; color:#6b7280; width:130px; vertical-align:top;">Bénéficiaire(s) :</td>
                                                <td style="padding:4px 0; font-size:14px; color:#111827; font-weight:600;">{{ $mission->beneficiaires->map(fn($b) => $b->prenom . ' ' . $b->nom)->implode(', ') }}</td>
                                            </tr>
                                            @endif
                                            @if($mission->remarques)
                                            <tr>
                                                <td style="padding:4px 0; font-size:14px; color:#6b7280; width:130px; vertical-align:top;">Remarques :</td>
                                                <td style="padding:4px 0; font-size:14px; color:#111827;">{{ $mission->remarques }}</td>
                                            </tr>
                                            @endif
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin:0; font-size:14px; color:#6b7280; line-height:1.5;">
                                Cette mission est maintenant de nouveau <strong style="color:#f97316;">disponible</strong>.
                            </p>
                        </td>
                    </tr>

                    {{-- Pied --}}
                    <tr>
                        <td style="padding:16px 32px; background:#f9fafb; border-top:1px solid #e5e7eb; text-align:center;">
                            <p style="margin:0; font-size:12px; color:#9ca3af;">Association Iroise — notification automatique</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
