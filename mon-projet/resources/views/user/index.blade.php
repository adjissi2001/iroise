@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="{{ asset('css/beneficiaires.css') }}">
    <link rel="stylesheet" href="{{ asset('css/missions.css') }}">
    <style>
        /* User modal overrides to match requested design */
        #createUserModal .modal-content {
            max-width: 760px;
            padding: 32px;
            border-radius: 18px;
            background: linear-gradient(180deg, rgba(255,255,255,0.98), rgba(250,250,250,0.98));
            box-shadow: 0 30px 80px rgba(8,15,40,0.35);
            border: 1px solid rgba(15,23,42,0.06);
        }

        #createUserModal {
            background: linear-gradient(180deg, rgba(10,20,30,0.38), rgba(10,20,30,0.38));
            backdrop-filter: blur(6px) saturate(120%);
            padding: 32px;
        }

        #createUserModal .modal-title {
            text-align: center;
            font-size: 26px;
            margin-bottom: 6px;
        }
        #createUserModal .modal-content > header p {
            text-align: center;
            color: #556075;
            margin-bottom: 18px;
        }

        /* Inputs bigger and rounded */
        #createUserModal .form-control,
        #createUserModal .form-row input,
        #createUserModal .form-row select,
        #createUserModal input[type="text"],
        #createUserModal input[type="email"],
        #createUserModal input[type="date"],
        #createUserModal input[type="password"],
        #createUserModal textarea {
            padding: 12px 14px;
            border-radius: 12px;
            border: 1px solid #e6e9ef;
            box-shadow: 0 4px 14px rgba(19, 40, 67, 0.04) inset;
            font-size: 14px;
        }

        /* Two-column grid spacing */
        #createUserModal .grid { gap: 18px; }

        /* Buttons */
        #createUserModal .btn-primary {
            background: linear-gradient(135deg,#16a34a,#22c55e);
            box-shadow: 0 8px 24px rgba(34,197,94,0.18);
            border-radius: 12px;
            padding: 10px 18px;
        }
        #createUserModal .btn-secondary {
            background: transparent;
            border: 1px solid #e6e9ef;
            color: #111827;
            border-radius: 12px;
            padding: 10px 18px;
        }

        /* Close button style */
        #createUserModal .modal-close {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 6px 18px rgba(8,15,40,0.12);
            width:36px;height:36px;line-height:36px;text-align:center;font-size:18px;
        }

        @media (max-width: 640px) {
            #createUserModal .modal-content { padding: 20px; border-radius: 14px; }
            #createUserModal .modal-title { font-size: 20px; }
        }
    </style>
@endpush

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800">
            {{ __('Gestion des Utilisateurs') }}
        </h2>
        <button type="button" class="btn-add-user" onclick="openCreateUserModal()">
            <i class="fas fa-plus mr-2"></i> Ajouter un utilisateur
        </button>
    </div>

    @if (session('success'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline"><i class="fas fa-check-circle"></i> {{ session('success') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</span>
        </div>
    @endif

    @if(($validatedUsers->isEmpty() ?? true) && ($pendingUsers->isEmpty() ?? true))
        <div class="text-center py-12 text-gray-600">
            <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <h3 class="mt-4 text-lg font-medium">Aucun utilisateur</h3>
            <p class="mt-1 text-sm">Aucun utilisateur enregistré dans le système.</p>
        </div>
    @else
        <div>
            <ul class="tabs inline-flex gap-4 mb-4">
                <li><button id="tab-validated" class="tab-button active">Utilisateurs validés ({{ $validatedUsers->count() }})</button></li>
                <li><button id="tab-pending" class="tab-button">Inscriptions en attente ({{ $pendingUsers->count() }})</button></li>
            </ul>

            <div id="validatedList" class="overflow-x-auto">
                <table id="validatedUsersTable" class="min-w-full users-table">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Rôle</th>
                            <th>Date d'inscription</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($validatedUsers as $user)
                            <tr>
                                <td data-label="Nom">{{ trim(optional($user->profil)->prenom.' '.optional($user->profil)->nom) }}</td>
                                <td data-label="Email">{{ $user->email }}</td>
                                <td data-label="Rôle">
                                    <span class="badge {{ $user->is_admin ? 'badge-admin' : 'badge-user' }}">
                                        {{ $user->is_admin ? 'Administrateur' : (optional($user->profil)->role ?? 'Utilisateur') }}
                                    </span>
                                </td>
                                <td data-label="Date d'inscription">{{ $user->created_at ? \Carbon\Carbon::parse($user->created_at)->format('d/m/Y') : 'N/A' }}</td>
                                <td data-label="Actions">
                                    <a href="{{ route('user.show', $user->id) }}" class="action-btn btn-view" title="Voir les détails">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if(auth()->user()->is_admin)
                                        <a href="{{ route('user.edit', $user->id) }}" class="action-btn btn-edit" title="Modifier">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>

                                        <form action="{{ route('user.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Confirmer la suppression ?');" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="action-btn btn-delete" title="Supprimer">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div id="pendingList" style="display:none;" class="overflow-x-auto">
                <table id="pendingUsersTable" class="min-w-full users-table">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Rôle</th>
                            <th>Date d'inscription</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingUsers as $user)
                            <tr>
                                <td data-label="Nom">{{ trim(optional($user->profil)->prenom.' '.optional($user->profil)->nom) }}</td>
                                <td data-label="Email">{{ $user->email }}</td>
                                <td data-label="Rôle">{{ optional($user->profil)->role ?? '-' }}</td>
                                <td data-label="Date d'inscription">{{ $user->created_at ? \Carbon\Carbon::parse($user->created_at)->format('d/m/Y') : 'N/A' }}</td>
                                <td data-label="Actions">
                                    <a href="{{ route('user.show', $user->id) }}" class="action-btn btn-view" title="Voir les détails"><i class="fas fa-eye"></i></a>
                                    @if(auth()->user()->is_admin)
                                        <a href="{{ route('user.edit', $user->id) }}" class="action-btn btn-edit" title="Modifier">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>

                                        <form action="{{ route('user.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Confirmer la suppression ?');" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="action-btn btn-delete" title="Supprimer">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        @include('user._register_modal')
    @endif

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
        <script>
            $(document).ready(function() {
                // Initialize DataTables for both lists
                $('.users-table').each(function() {
                    $(this).DataTable({
                        orderCellsTop: true,
                        pageLength: 10,
                        language: {
                            url: "//cdn.datatables.net/plug-ins/1.13.8/i18n/fr-FR.json"
                        }
                    });
                });

                // Tabs for validated / pending
                document.getElementById('tab-validated').addEventListener('click', function() {
                    document.getElementById('validatedList').style.display = '';
                    document.getElementById('pendingList').style.display = 'none';
                });
                document.getElementById('tab-pending').addEventListener('click', function() {
                    document.getElementById('validatedList').style.display = 'none';
                    document.getElementById('pendingList').style.display = '';
                });
            });
        </script>
    @endpush
@endsection
