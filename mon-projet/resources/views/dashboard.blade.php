@extends('layouts.app')

@section('content')
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-300 text-green-800 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @php
                $roleProfil = optional(auth()->user()->profil)->role;
                $isAdmin = auth()->user()->is_admin;
                $userName = auth()->user()->display_name;
            @endphp

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-8 mb-8">
                
                <!-- Message de bienvenue personnalisé -->
                @if($isAdmin)
                    <h2 class="font-bold text-3xl text-blue-600 dark:text-blue-400 mb-4">
                        {{ $userName }}, Bienvenue dans votre espace administrateur !
                    </h2>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">
                        Vous avez accès à l'ensemble des fonctionnalités de gestion de l'application.
                    </p>
                @elseif($roleProfil === 'referent')
                    <h2 class="font-bold text-3xl text-green-600 dark:text-green-400 mb-4">
                         {{ $userName }}, Bienvenue dans votre espace référent !
                    </h2>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">
                        Vous pouvez gérer vos bénéficiaires et consulter les missions assignées.
                    </p>
                @elseif($roleProfil === 'benevole')
                    <h2 class="font-bold text-3xl text-purple-600 dark:text-purple-400 mb-4">
                          {{ $userName }} ,Bienvenue dans votre espace bénévole !
                    </h2>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">
                        Merci pour votre engagement ! Consultant vos missions et votre contribution.
                    </p>
                @else
                    <h2 class="font-bold text-3xl text-gray-700 dark:text-gray-300 mb-4">
                         Bienvenue, {{ $userName }} !
                    </h2>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">
                        Utilisez le menu de navigation pour accéder aux différentes sections de l'application.
                    </p>
                @endif

            </div>

            <!-- Contenu spécifique par rôle -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-8">
                
                @if($isAdmin)
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4"> Espace Administrateur</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <a href="{{ route('admin.statistics.index') }}" class="p-4 bg-blue-50 dark:bg-blue-900 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-800 transition">
                                <h4 class="font-semibold text-blue-900 dark:text-blue-100 mb-1"> Statistiques</h4>
                                <p class="text-sm text-blue-700 dark:text-blue-200">Consultez les performances</p>
                            </a>
                            <a href="{{ route('admin.missions.index') }}" class="p-4 bg-indigo-50 dark:bg-indigo-900 rounded-lg hover:bg-indigo-100 dark:hover:bg-indigo-800 transition">
                                <h4 class="font-semibold text-indigo-900 dark:text-indigo-100 mb-1"> Missions</h4>
                                <p class="text-sm text-indigo-700 dark:text-indigo-200">Gérer les missions</p>
                            </a>
                            <a href="{{ route('user.index') }}" class="p-4 bg-purple-50 dark:bg-purple-900 rounded-lg hover:bg-purple-100 dark:hover:bg-purple-800 transition">
                                <h4 class="font-semibold text-purple-900 dark:text-purple-100 mb-1"> Utilisateurs</h4>
                                <p class="text-sm text-purple-700 dark:text-purple-200">Gérer les utilisateurs</p>
                            </a>
                        </div>
                    </div>

                @elseif($roleProfil === 'referent')
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4"> Espace Référent</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <a href="{{ route('beneficiaire.index') }}" class="p-4 bg-green-50 dark:bg-green-900 rounded-lg hover:bg-green-100 dark:hover:bg-green-800 transition">
                                <h4 class="font-semibold text-green-900 dark:text-green-100 mb-1"> Mes Bénéficiaires</h4>
                                <p class="text-sm text-green-700 dark:text-green-200">Consulter et gérer vos bénéficiaires</p>
                            </a>
                            <a href="{{ route('admin.missions.index') }}" class="p-4 bg-blue-50 dark:bg-blue-900 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-800 transition">
                                <h4 class="font-semibold text-blue-900 dark:text-blue-100 mb-1"> Missions</h4>
                                <p class="text-sm text-blue-700 dark:text-blue-200">Consulter les missions assignées</p>
                            </a>
                        </div>
                    </div>

                @elseif($roleProfil === 'benevole')
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">🙌 Espace Bénévole</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <a href="{{ route('beneficiaire.index') }}" class="p-4 bg-purple-50 dark:bg-purple-900 rounded-lg hover:bg-purple-100 dark:hover:bg-purple-800 transition">
                                <h4 class="font-semibold text-purple-900 dark:text-purple-100 mb-1">📍 Bénéficiaires</h4>
                                <p class="text-sm text-purple-700 dark:text-purple-200">Voir les bénéficiaires</p>
                            </a>
                            <a href="{{ route('admin.missions.index') }}" class="p-4 bg-pink-50 dark:bg-pink-900 rounded-lg hover:bg-pink-100 dark:hover:bg-pink-800 transition">
                                <h4 class="font-semibold text-pink-900 dark:text-pink-100 mb-1">✅ Mes Missions</h4>
                                <p class="text-sm text-pink-700 dark:text-pink-200">Consulter vos missions</p>
                            </a>
                        </div>
                    </div>

                @endif

                <div class="mt-8 pt-8 border-t border-gray-200 dark:border-gray-700">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Utilisez le menu de navigation pour accéder à l'ensemble des sections de l'application.
                    </p>
                </div>

            </div>

        </div>
    </div>
@endsection
