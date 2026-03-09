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
            @php
                $pillActive = 'bg-gray-800 text-white';
                $pillInactive = 'bg-gray-200 text-gray-800 hover:bg-gray-300';
            @endphp

            <div class="mb-4 flex flex-wrap items-center gap-2">
                <button id="tab-pending"
                        type="button"
                        class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium transition {{ $pillInactive }}"
                        aria-controls="pendingList"
                        aria-selected="false">
                    Inscriptions en attente ({{ $pendingUsers->count() }})
                </button>
                <button id="tab-validated"
                        type="button"
                        class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium transition {{ $pillActive }}"
                        aria-controls="validatedList"
                        aria-selected="true">
                    Utilisateurs validés ({{ $validatedUsers->count() }})
                </button>
            </div>

            <div id="validatedList" class="overflow-x-auto">
                <div class="mb-4 flex flex-wrap items-center gap-2">
                    <button id="tab-lr"
                            type="button"
                            class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium transition {{ $pillActive }}"
                            aria-controls="lrList"
                            aria-selected="true">
                        LR — Référents actifs ({{ ($lrReferents ?? collect())->count() }})
                    </button>
                    <button id="tab-lar"
                            type="button"
                            class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium transition {{ $pillInactive }}"
                            aria-controls="larList"
                            aria-selected="false">
                        LAR — Anciens référents ({{ ($larReferents ?? collect())->count() }})
                    </button>
                </div>

                <div id="lrList">
                    <table id="lrUsersTable" class="min-w-full users-table">
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
                            @foreach(($lrReferents ?? collect()) as $user)
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
                                            <form action="{{ route('user.toggleActif', $user->id) }}" method="POST" onsubmit="return confirm('Désactiver cet utilisateur ?');" style="display:inline;">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="action-btn btn-edit" title="Désactiver">
                                                    <i class="fa-solid fa-user-slash"></i>
                                                </button>
                                            </form>

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

                <div id="larList" style="display:none;">
                    <table id="larUsersTable" class="min-w-full users-table">
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
                            @foreach(($larReferents ?? collect()) as $user)
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
                                            <form action="{{ route('user.toggleActif', $user->id) }}" method="POST" onsubmit="return confirm('Activer cet utilisateur ?');" style="display:inline;">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="action-btn btn-edit" title="Activer">
                                                    <i class="fa-solid fa-user-check"></i>
                                                </button>
                                            </form>

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

            <div id="pendingList" style="display:none;" class="overflow-x-auto">
                <div class="mb-3 flex flex-wrap items-center justify-between gap-2">
                    <div class="text-sm text-gray-600">
                        @php
                            $thresholdMinutes = (int) ($pendingExpirationMinutes ?? (48 * 60));
                            $thresholdLabel = $thresholdMinutes >= 60
                                ? (intdiv($thresholdMinutes, 60) . 'h')
                                : ($thresholdMinutes . 'min');
                        @endphp
                        <span>En rouge : inscriptions en attente au-delà du seuil ({{ $thresholdLabel }}).</span>
                        @if(!empty($pendingExpiredCount))
                            <span class="ml-2 font-semibold text-red-700">({{ $pendingExpiredCount }} expirée(s))</span>
                        @endif
                    </div>

                    @if(auth()->user()->is_admin && !empty($pendingExpiredCount))
                        <form action="{{ route('user.destroyExpiredPending') }}" method="POST" onsubmit="return confirm('Supprimer tous les comptes en attente dépassant le délai ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded">
                                Supprimer les comptes expirés
                            </button>
                        </form>
                    @endif
                </div>

                <table id="pendingUsersTable" class="min-w-full users-table">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Rôle</th>
                            <th>Date d'inscription</th>
                            <th>Délai</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingUsers as $user)
                            @php
                                $createdAt = $user->created_at ? \Carbon\Carbon::parse($user->created_at) : null;
                                $expirationMinutes = (int) ($pendingExpirationMinutes ?? (48 * 60));
                                $deadline = $createdAt ? $createdAt->copy()->addMinutes($expirationMinutes) : null;
                                $remainingMinutes = $deadline ? now()->diffInMinutes($deadline, false) : null;
                                $isExpired = $remainingMinutes !== null ? ($remainingMinutes <= 0) : false;

                                $elapsedMinutes = $createdAt ? $createdAt->diffInMinutes(now()) : null;
                                $overMinutes = ($isExpired && $deadline) ? $deadline->diffInMinutes(now()) : null;

                                $formatHM = function (?int $minutes): string {
                                    if ($minutes === null) {
                                        return '-';
                                    }
                                    $minutes = max(0, $minutes);
                                    $h = intdiv($minutes, 60);
                                    $m = $minutes % 60;
                                    return sprintf('%dh %02dm', $h, $m);
                                };
                            @endphp
                            <tr class="{{ $isExpired ? 'bg-red-50' : '' }}">
                                <td data-label="Nom">{{ trim(optional($user->profil)->prenom.' '.optional($user->profil)->nom) }}</td>
                                <td data-label="Email">{{ $user->email }}</td>
                                <td data-label="Rôle">{{ optional($user->profil)->role ?? '-' }}</td>
                                <td data-label="Date d'inscription">
                                    {{ $createdAt ? $createdAt->format('d/m/Y') : 'N/A' }}
                                    @if($isExpired)
                                        <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-red-100 text-red-700">
                                            Délai dépassé
                                        </span>
                                    @endif
                                </td>
                                <td data-label="Délai">
                                    @if($elapsedMinutes === null)
                                        -
                                    @else
                                        <div class="leading-tight {{ $isExpired ? 'text-red-700 font-semibold' : '' }}">
                                            {{ $formatHM($elapsedMinutes) }}
                                        </div>

                                        <div class="text-xs text-gray-500">
                                            @if($remainingMinutes !== null && $remainingMinutes > 0)
                                                Reste {{ $formatHM($remainingMinutes) }} (seuil {{ $thresholdLabel }})
                                            @elseif($overMinutes !== null)
                                                Dépassé de {{ $formatHM($overMinutes) }} (seuil {{ $thresholdLabel }})
                                            @else
                                                (seuil {{ $thresholdLabel }})
                                            @endif
                                        </div>
                                    @endif
                                </td>
                                <td data-label="Actions">
                                    <a href="{{ route('user.show', $user->id) }}" class="action-btn btn-view" title="Voir les détails"><i class="fas fa-eye"></i></a>
                                    @if(auth()->user()->is_admin)
                                        <a href="{{ route('user.edit', $user->id) }}" class="action-btn btn-edit" title="Modifier">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>

                                        @if($isExpired)
                                            <form action="{{ route('user.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Confirmer la suppression ?');" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="action-btn btn-delete" title="Supprimer">
                                                    <i class="fa-solid fa-trash-can"></i>
                                                </button>
                                            </form>
                                        @else
                                            <button type="button" class="action-btn btn-delete" title="Suppression disponible uniquement après dépassement du délai" disabled style="opacity:.35;cursor:not-allowed;">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        @endif
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

                function setPillActive($btn, active) {
                    if (!$btn) return;
                    $btn.classList.remove('bg-gray-800', 'text-white', 'bg-gray-200', 'text-gray-800', 'hover:bg-gray-300');
                    if (active) {
                        $btn.classList.add('bg-gray-800', 'text-white');
                        $btn.setAttribute('aria-selected', 'true');
                    } else {
                        $btn.classList.add('bg-gray-200', 'text-gray-800', 'hover:bg-gray-300');
                        $btn.setAttribute('aria-selected', 'false');
                    }
                }

                // Tabs for validated / pending
                var tabValidated = document.getElementById('tab-validated');
                var tabPending = document.getElementById('tab-pending');

                tabValidated.addEventListener('click', function() {
                    document.getElementById('validatedList').style.display = '';
                    document.getElementById('pendingList').style.display = 'none';
                    setPillActive(tabValidated, true);
                    setPillActive(tabPending, false);
                });
                tabPending.addEventListener('click', function() {
                    document.getElementById('validatedList').style.display = 'none';
                    document.getElementById('pendingList').style.display = '';
                    setPillActive(tabValidated, false);
                    setPillActive(tabPending, true);
                });

                // Sub-tabs LR/LAR in validated
                var tabLr = document.getElementById('tab-lr');
                var tabLar = document.getElementById('tab-lar');

                tabLr.addEventListener('click', function() {
                    setPillActive(tabLr, true);
                    setPillActive(tabLar, false);
                    document.getElementById('lrList').style.display = '';
                    document.getElementById('larList').style.display = 'none';

                    try { $('#lrUsersTable').DataTable().columns.adjust(); } catch (e) {}
                });
                tabLar.addEventListener('click', function() {
                    setPillActive(tabLr, false);
                    setPillActive(tabLar, true);
                    document.getElementById('lrList').style.display = 'none';
                    document.getElementById('larList').style.display = '';

                    try { $('#larUsersTable').DataTable().columns.adjust(); } catch (e) {}
                });
            });
        </script>
    @endpush
@endsection
