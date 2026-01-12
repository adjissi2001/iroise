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
        @endphp
        @if(in_array($roleProfil, ['referent', 'benevole']))
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

    @if($beneficiaires->isEmpty())
        <div class="text-center py-12 text-gray-600">
            <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <h3 class="mt-4 text-lg font-medium">Aucun bénéficiaire</h3>
            <p class="mt-1 text-sm">Vous n'avez pas encore de bénéficiaires enregistrés.</p>
        </div>
    @else
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
                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer {{ $beneficiaire->prenom }} {{ $beneficiaire->nom }} ?');">
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
    @endif

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
        <script src="{{ asset('js/beneficiaires.js') }}"></script>
    @endpush
@endsection
