@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">
        <!-- En-tête de l'espace référent -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Espace Référent</h1>
            <p class="text-gray-600 mt-2">Bienvenue dans votre espace de gestion</p>
        </div>

        <!-- Zone principale -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Tableau de bord</h2>
            @if(optional(auth()->user()->profil)->est_valide == 0)
                <div class="mb-4 p-4 bg-yellow-100 border border-yellow-300 rounded">
                    <strong>Action requise :</strong> Vous devez modifier votre mot de passe pour valider votre compte. <a href="{{ route('profile.edit') }}" class="text-blue-600 underline">Cliquez ici pour modifier</a>.
                </div>
            @endif
            
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <p class="text-gray-500 mt-4">Utilisez le menu en haut pour accéder aux bénéficiaires et utilisateurs</p>
            </div>
        </div>
    </div>
</div>
@endsection
