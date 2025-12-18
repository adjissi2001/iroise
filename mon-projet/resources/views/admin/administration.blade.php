<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Espace Administrateur - Iroise</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .container {
            flex: 1; /* pousse le footer en bas */
        }

       body {
                font-family: 'Inter', sans-serif;
                background: linear-gradient(145deg, #eef2ff, #f8fafc);
                margin: 0;
                padding: 0;
                min-height: 100vh;
            }


        /* 🌟 En-tête */
        header {
            background: linear-gradient(90deg, #0EA5E9, #2563EB);
            color: white;
            padding: 15px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
        }

        header h1 {
            margin: 0;
            font-size: 22px;
            letter-spacing: 1px;
        }

        header a {
            background-color: white;
            color: #007BFF;
            padding: 8px 15px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: 0.3s;
        }

        header a:hover {
            background-color: #0056b3;
            color: white;
        }

            .container {
            background: #ffffff;
            margin: 40px auto;
            padding: 40px;
            border-radius: 18px;
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.08);
            width: 85%;
            max-width: 1100px;
            text-align: center;
        }


        /* ✨ Animation d'apparition */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* 🧾 Titre */
        h2 {
            color: #333;
            margin-bottom: 20px;
            font-size: 1.6rem;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 10px;
        }

        thead tr {
            background: #4f46e5;
            color: white;
            border-radius: 8px;
        }

        th {
            padding: 14px;
            background: #888892ff;
            font-size: 14px;
            text-transform: uppercase;
        }

        tbody tr {
            background: #ffffff;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
            border-radius: 10px;
        }

        tbody td {
            padding: 14px;
            color: #374151;
            font-size: 14px;
        }


        tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        tr:hover {
            background-color: #e9f2ff;
            transition: 0.2s;
        }

        /* 📱 Responsive */
        @media (max-width: 768px) {
            .container {
                width: 95%;
                padding: 20px;
            }
            table, thead, tbody, th, td, tr {
                display: block;
            }
            thead tr {
                display: none;
            }
            tbody tr {
                margin-bottom: 15px;
                background-color: #fdfdfd;
                border-radius: 8px;
                padding: 10px;
                box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            }
            td {
                text-align: right;
                padding-left: 50%;
                position: relative;
            }
            td::before {
                content: attr(data-label);
                position: absolute;
                left: 15px;
                width: 45%;
                font-weight: bold;
                color: #333;
                text-align: left;
            }
        }
            /* 🎨 Boutons d'action */
            .action-btn {
                background: #f0f4ff;
                border: none;
                padding: 8px 10px;
                margin: 0 4px;
                border-radius: 10px;
                cursor: pointer;
                font-size: 16px;
                transition: 0.25s ease;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 38px;
                height: 38px;
            }

            /* Icônes */
            .action-btn i {
                font-size: 18px;
                transition: 0.25s ease;
            }

            /* Modifier */
            .btn-edit {
                background: #e8f0ff;
            }
            .btn-edit i {
                color: #007bff;
            }
            .btn-edit:hover {
                background: #007bff;
            }
            .btn-edit:hover i {
                color: white;
                transform: scale(1.15);
            }

            /* Supprimer */
            .btn-delete {
                background: #ffe8e8;
            }
            .btn-delete i {
                color: #dc3545;
            }
            .btn-delete:hover {
                background: #dc3545;
            }
            .btn-delete:hover i {
                color: white;
                transform: scale(1.15);
            }

        .btn-view {
            background: #17a2b8;
            color: #fff;
        }
        .btn-action:hover {
            opacity: 0.8;
        }
        .footer {
            margin-top: auto;
        }


    </style>
</head>
<body>

    {{-- 🔹 Inclusion du haut de page --}}
    @include('layouts.haut')

    <div class="container">
        <h2>Liste des bénéficiaires</h2>

        @if(!empty($errorMessage))
            <div style="background:#fee2e2;color:#991b1b;border:1px solid #fecaca;padding:12px 16px;border-radius:10px;margin-bottom:16px;text-align:left;">
                {{ $errorMessage }}
            </div>
        @endif

        @if(!empty($beneficiaires))
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Date de naissance</th>
                        <th>Téléphone</th>
                        <th>Email</th>
                        <th>Actions</th> <!-- ➕ Nouvelle colonne -->
                    </tr>
                </thead>

                <tbody>
                    @foreach ($beneficiaires as $b)
                        <tr>
                            <td data-label="ID">{{ $b->id_beneficiaire }}</td>
                            <td data-label="Nom">{{ $b->nom }}</td>
                            <td data-label="Prénom">{{ $b->prenom }}</td>
                            <td data-label="Date de naissance">{{ $b->date_naissance }}</td>
                            <td data-label="Téléphone">{{ $b->num_tel }}</td>
                            <td data-label="Email">{{ $b->email }}</td>

                      <td data-label="Actions" style="text-align: center;">

                            <!-- Modifier -->
                            <form action="{{ route('beneficiaire.updateSql', $b->id_beneficiaire) }}"
                                method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="action-btn btn-edit" title="Modifier">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                            </form>

                           <form action="{{ route('beneficiaire.destroy', $b->id_beneficiaire) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="action-btn btn-delete" title="Supprimer"
                                        onclick="return confirm('Confirmer la suppression ?');">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </td>


                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>Aucun bénéficiaire trouvé.</p>
        @endif
    </div>

    {{-- 🔹 Inclusion du bas de page --}}
    @include('layouts.bas')

</body>
</html>
