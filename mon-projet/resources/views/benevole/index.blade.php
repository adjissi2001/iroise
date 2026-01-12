@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">
        <!-- En-tête de l'espace bénévole -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Espace Bénévole</h1>
            <p class="text-gray-600 mt-2">Bienvenue dans votre espace personnel</p>
        </div>

        <!-- Zone pour les missions (vide au départ) -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Mes Missions</h2>
            
            <div id="missions-container" class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <p class="text-gray-500 mt-4">Aucune mission pour le moment</p>
                <p class="text-gray-400 text-sm mt-2">Les missions de l'agenda seront affichées ici</p>
            </div>
        </div>

        <!-- Zone pour l'intégration future de l'API agenda -->
        <div class="mt-8">
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-start">
                    <svg class="h-6 w-6 text-blue-600 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <h3 class="text-sm font-medium text-blue-800">Intégration API Agenda</h3>
                        <p class="text-sm text-blue-700 mt-1">L'agenda des missions sera intégré prochainement</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script pour l'intégration future de l'API -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Espace bénévole chargé - Prêt pour l\'intégration API');
    });
</script>
@endsection
