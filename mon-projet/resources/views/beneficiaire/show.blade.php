<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Détails du Bénéficiaire') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-2xl font-bold">{{ $beneficiaire->prenom }} {{ $beneficiaire->nom }}</h3>
                        <a href="{{ route('beneficiaire.index') }}" class="text-indigo-600 hover:text-indigo-900">
                            ← Retour à la liste
                        </a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nom</label>
                            <p class="mt-1 text-lg">{{ $beneficiaire->nom }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Prénom</label>
                            <p class="mt-1 text-lg">{{ $beneficiaire->prenom }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <p class="mt-1 text-lg">{{ $beneficiaire->email ?? 'Non renseigné' }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Téléphone</label>
                            <p class="mt-1 text-lg">{{ $beneficiaire->num_tel ?? 'Non renseigné' }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Date de naissance</label>
                            <p class="mt-1 text-lg">
                                {{ $beneficiaire->date_naissance ? \Carbon\Carbon::parse($beneficiaire->date_naissance)->format('d/m/Y') : 'Non renseignée' }}
                            </p>
                        </div>

                        @if($beneficiaire->date_naissance)
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Âge</label>
                            <p class="mt-1 text-lg">
                                {{ \Carbon\Carbon::parse($beneficiaire->date_naissance)->age }} ans
                            </p>
                        </div>
                        @endif
                    </div>

                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <form action="{{ route('beneficiaire.destroy', $beneficiaire->id_beneficiaire) }}" 
                              method="POST" 
                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce bénéficiaire ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Supprimer ce bénéficiaire
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
