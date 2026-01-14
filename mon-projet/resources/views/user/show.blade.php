@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
@endpush

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Détails de l\'utilisateur') }}
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <a href="{{ route('user.index') }}" class="text-blue-600 hover:text-blue-800 mb-6 inline-block">
            <i class="fas fa-arrow-left"></i> Retour à la liste
        </a>

        <div class="card-glass rounded-lg shadow-lg p-8">
            <div class="mb-6">
                <a href="{{ route('user.index') }}" class="inline-block text-sm text-gray-500 hover:text-gray-700 mb-4">
                    <i class="fas fa-chevron-left"></i> Retour
                </a>
            </div>

            <div class="grid grid-cols-2 gap-6">
                <!-- Nom -->
                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wide">Nom</label>
                    <p class="text-lg font-semibold text-gray-900 mt-1">{{ trim($user->prenom.' '.$user->nom) }}</p>
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wide">Email</label>
                    <p class="text-lg font-semibold text-gray-900 mt-1">
                        <a href="mailto:{{ $user->email }}" class="text-blue-600 hover:text-blue-800">
                            {{ $user->email }}
                        </a>
                    </p>
                </div>

                <!-- Statut -->
                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wide">Statut</label>
                    <p class="text-lg font-semibold mt-1">
                        <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold {{ $user->is_admin ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                            {{ $user->is_admin ? 'Administrateur' : 'Utilisateur' }}
                        </span>
                    </p>
                </div>

                <!-- Date d'inscription -->
                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wide">Date d'inscription</label>
                    <p class="text-lg font-semibold text-gray-900 mt-1">
                        {{ $user->created_at ? \Carbon\Carbon::parse($user->created_at)->format('d/m/Y à H:i') : 'N/A' }}
                    </p>
                </div>

                <!-- Dernière connexion -->
                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wide">Dernière mise à jour</label>
                    <p class="text-lg font-semibold text-gray-900 mt-1">
                        {{ $user->updated_at ? \Carbon\Carbon::parse($user->updated_at)->format('d/m/Y à H:i') : 'N/A' }}
                    </p>
                </div>

                <!-- Nombre de bénéficiaires -->
                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wide">Bénéficiaires</label>
                    <p class="text-lg font-semibold text-gray-900 mt-1">
                        <span class="inline-block px-3 py-1 rounded-full bg-blue-100 text-blue-800">
                            {{ $user->beneficiaires->count() }}
                        </span>
                    </p>
                </div>
            </div>

            @if($user->beneficiaires->count() > 0)
                <div class="mt-8 pt-8 border-t border-gray-200">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">
                        <i class="fas fa-users text-blue-600"></i> Bénéficiaires de cet utilisateur
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($user->beneficiaires as $beneficiaire)
                            <div class="p-4 border border-gray-200 rounded-lg hover:shadow-md transition-shadow">
                                <p class="font-semibold text-gray-900">
                                    {{ $beneficiaire->prenom }} {{ $beneficiaire->nom }}
                                </p>
                                <p class="text-sm text-gray-600">
                                    <i class="fas fa-envelope"></i> {{ $beneficiaire->email ?? 'N/A' }}
                                </p>
                                <p class="text-sm text-gray-600">
                                    <i class="fas fa-phone"></i> {{ $beneficiaire->num_tel ?? 'N/A' }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="mt-8 text-center">
                <a href="{{ route('user.index') }}" class="inline-block px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-arrow-left"></i> Retour à la liste
                </a>
            </div>
        </div>
    </div>

</x-app-layout>
