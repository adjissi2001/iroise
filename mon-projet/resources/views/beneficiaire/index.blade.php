```php
@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="{{ asset('css/beneficiaires.css') }}">
@endpush

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800">
            {{ __('Mes Bénéficiaires') }}
        </h2>
        @php
            $roleProfil = optional(auth()->user()->profil)->role;
            $canCreateBeneficiaire = (bool) (auth()->user()->is_admin ?? false) || in_array($roleProfil, ['referent', 'benevole']);
        @endphp
        @if($canCreateBeneficiaire)
            <a href="{{ route('beneficiaire.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-900 transition">
                <i class="fas fa-plus mr-2"></i> Ajouter un bénéficiaire
            </a>
        @endif
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

    @php
        $segment = $segment ?? 'LB';
        $countLB = $countLB ?? 0;
        $countLAB = $countLAB ?? 0;
    @endphp

    <div class="mb-4 flex flex-wrap items-center gap-2">
        <a href="{{ route('beneficiaire.index', ['segment' => 'LB']) }}"
           class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium transition {{ $segment === 'LB' ? 'bg-gray-800 text-white' : 'bg-gray-200 text-gray-800 hover:bg-gray-300' }}">
            LB — Actifs ({{ $countLB }})
        </a>
        <a href="{{ route('beneficiaire.index', ['segment' => 'LAB']) }}"
           class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium transition {{ $segment === 'LAB' ? 'bg-gray-800 text-white' : 'bg-gray-200 text-gray-800 hover:bg-gray-300' }}">
            LAB — Inactifs ({{ $countLAB }})
        </a>
    </div>

    @if($beneficiaires->isEmpty())
        <div class="mb-4 text-center py-6 text-gray-600">
            <h3 class="text-lg font-medium">Aucun bénéficiaire</h3>
            <p class="mt-1 text-sm">
                {{ $segment === 'LB' ? 'Aucun bénéficiaire actif (LB) à afficher.' : 'Aucun bénéficiaire inactif (LAB) à afficher.' }}
            </p>
        </div>
    @endif

    <div class="overflow-x-auto">
        <table id="beneficiairesTable" class="min-w-full">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                    <th>Date de naissance</th>
                    <th>Actions</th>
                </tr>
                <tr>
                    <th><input type="text" placeholder="Nom" /></th>
                    <th><input type="text" placeholder="Prénom" /></th>
                    <th><input type="text" placeholder="Email" /></th>
                    <th><input type="text" placeholder="Téléphone" /></th>
                    <th><input type="text" placeholder="Date" /></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($beneficiaires as $beneficiaire)
                    @php
                        $roleProfil = optional(auth()->user()->profil)->role;
                        $isAdmin = auth()->user()->is_admin ?? false;
                        $isReferent = $roleProfil === 'referent';
                        $isProprietaire = (int) ($beneficiaire->user_id ?? 0) === (int) (auth()->id() ?? 0);
                        $isActive = (int) ($beneficiaire->actif ?? 1) === 1;
                    @endphp
                    <tr>
                        <td data-label="Nom">{{ $beneficiaire->nom }}</td>
                        <td data-label="Prénom">{{ $beneficiaire->prenom }}</td>
                        <td data-label="Email">{{ $beneficiaire->email ?? 'N/A' }}</td>
                        <td data-label="Téléphone">{{ $beneficiaire->num_tel ?? 'N/A' }}</td>
                        <td data-label="Date de naissance">
                            {{ $beneficiaire->date_naissance ? \Carbon\Carbon::parse($beneficiaire->date_naissance)->format('d/m/Y') : 'N/A' }}
                        </td>
                        <td data-label="Actions">
                            <a href="{{ route('beneficiaire.show', $beneficiaire->id_beneficiaire) }}" 
                               class="action-btn btn-view"
                               title="Voir les détails">
                                <i class="fas fa-eye"></i>
                            </a>

                            @if($isAdmin || $isReferent)
                                <a href="{{ route('beneficiaire.edit', $beneficiaire->id_beneficiaire) }}"
                                   class="action-btn btn-edit"
                                   title="Modifier">
                                    <i class="fas fa-pen"></i>
                                </a>
                            @endif

                            @if($isAdmin || $isProprietaire)
                                <form action="{{ route('beneficiaire.toggleActif', $beneficiaire->id_beneficiaire) }}"
                                      method="POST"
                                      style="display: inline;"
                                      onsubmit="return confirm('{{ $isActive ? 'Désactiver' : 'Activer' }} {{ $beneficiaire->prenom }} {{ $beneficiaire->nom }} ?');">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="action-btn btn-edit" title="{{ $isActive ? 'Désactiver' : 'Activer' }}">
                                        <i class="fas {{ $isActive ? 'fa-user-slash' : 'fa-user-check' }}"></i>
                                    </button>
                                </form>
                            @endif

                            @if($isAdmin || $isReferent)
                                <form action="{{ route('beneficiaire.destroy', $beneficiaire->id_beneficiaire) }}" 
                                      method="POST" 
                                      style="display: inline;"
                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer {{ $beneficiaire->prenom }} {{ $beneficiaire->nom }} ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn btn-delete" title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
        <script src="{{ asset('js/beneficiaires.js') }}"></script>
    @endpush
@endsection