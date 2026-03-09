@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="{{ asset('css/beneficiaires.css') }}">
@endpush

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">
        <!-- En-tête de l'espace référent -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Espace Référent</h1>
            <p class="text-gray-600 mt-2">Bienvenue dans votre espace de gestion</p>
        </div>

        @if(optional(auth()->user()->profil)->est_valide == 0)
            <div class="mb-4 p-4 bg-yellow-100 border border-yellow-300 rounded">
                <strong>Action requise :</strong> Vous devez modifier votre mot de passe pour valider votre compte.
                <a href="{{ route('profile.edit') }}" class="text-blue-600 underline">Cliquez ici pour modifier</a>.
            </div>
        @endif

        @if(!empty($errorMessage))
            <div class="mb-4 p-4 bg-red-100 border border-red-300 text-red-800 rounded">
                {{ $errorMessage }}
            </div>
        @endif

        <div class="mb-6 flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">
                {{ __('Mes Bénéficiaires') }}
            </h2>
            <a href="{{ route('beneficiaire.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-900 transition">
                <i class="fas fa-plus mr-2"></i> Ajouter un bénéficiaire
            </a>
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
            <a href="{{ route('referent.index', ['segment' => 'LB']) }}"
               class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium transition {{ $segment === 'LB' ? 'bg-gray-800 text-white' : 'bg-gray-200 text-gray-800 hover:bg-gray-300' }}">
                LB — Actifs ({{ $countLB }})
            </a>
            <a href="{{ route('referent.index', ['segment' => 'LAB']) }}"
               class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium transition {{ $segment === 'LAB' ? 'bg-gray-800 text-white' : 'bg-gray-200 text-gray-800 hover:bg-gray-300' }}">
                LAB — Inactifs ({{ $countLAB }})
            </a>
        </div>

        @if(($beneficiaires ?? collect())->isEmpty())
            <div class="mb-4 text-center py-6 text-gray-600">
                <h3 class="text-lg font-medium">Aucun bénéficiaire</h3>
                <p class="mt-1 text-sm">
                    {{ $segment === 'LB' ? 'Aucun bénéficiaire actif (LB) à afficher.' : 'Aucun bénéficiaire inactif (LAB) à afficher.' }}
                </p>
            </div>
        @endif

        <div class="overflow-x-auto bg-white rounded-lg shadow-md p-6">
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
                    @foreach(($beneficiaires ?? collect()) as $beneficiaire)
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
                                <form action="{{ route('beneficiaire.destroy', $beneficiaire->id_beneficiaire) }}"
                                      method="POST"
                                      style="display: inline;"
                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ' + @json($beneficiaire->prenom) + ' ' + @json($beneficiaire->nom) + ' ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn btn-delete" title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('js/beneficiaires.js') }}"></script>
@endpush
@endsection
