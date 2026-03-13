@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
@endpush

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Détails du bénéficiaire') }}
            </h2>
            <a href="{{ route('beneficiaire.index') }}" class="text-indigo-600 hover:text-indigo-800 inline-flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> Retour à la liste
            </a>
        </div>
    </x-slot>

    @if (session('success'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline"><i class="fas fa-check-circle mr-2"></i>{{ session('success') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline"><i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}</span>
        </div>
    @endif

    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h3 class="text-2xl font-bold text-gray-900">{{ $beneficiaire->prenom }} {{ $beneficiaire->nom }}</h3>
            <span class="text-sm text-gray-500">ID #{{ $beneficiaire->id_beneficiaire }}</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-xs uppercase tracking-wider text-gray-500">Nom</label>
                <p class="mt-1 text-lg font-medium">{{ $beneficiaire->nom }}</p>
            </div>
            <div>
                <label class="block text-xs uppercase tracking-wider text-gray-500">Prénom</label>
                <p class="mt-1 text-lg font-medium">{{ $beneficiaire->prenom }}</p>
            </div>
            <div>
                <label class="block text-xs uppercase tracking-wider text-gray-500">Email</label>
                <p class="mt-1 text-lg">{{ $beneficiaire->email ?? 'Non renseigné' }}</p>
            </div>
            <div>
                <label class="block text-xs uppercase tracking-wider text-gray-500">Téléphone</label>
                <p class="mt-1 text-lg">{{ $beneficiaire->num_tel ?? 'Non renseigné' }}</p>
            </div>
            <div>
                <label class="block text-xs uppercase tracking-wider text-gray-500">Date de naissance</label>
                <p class="mt-1 text-lg">
                    {{ $beneficiaire->date_naissance ? \Carbon\Carbon::parse($beneficiaire->date_naissance)->format('d/m/Y') : 'Non renseignée' }}
                </p>
            </div>
            <div>
                <label class="block text-xs uppercase tracking-wider text-gray-500">adresse Postale</label>
                <p class="mt-1 text-lg">{{ $beneficiaire->adresse ?? 'Non renseigné' }}</p>
            </div>
            <div>
                <label class="block text-xs uppercase tracking-wider text-gray-500">Code Postale</label>
                <p class="mt-1 text-lg">{{ $beneficiaire->code_postal ?? 'Non renseigné' }}</p>
            </div>
            <div>
                <label class="block text-xs uppercase tracking-wider text-gray-500">Commune</label>
                <p class="mt-1 text-lg">{{ $beneficiaire->Commune ?? 'Non renseigné' }}</p>
            </div>
            <div>
                <label class="block text-xs uppercase tracking-wider text-gray-500">Remarques</label>
                <p class="mt-1 text-lg">{{ $beneficiaire->remarques ?? 'Non renseigné' }}</p>
            </div>
            @if($beneficiaire->date_naissance)
                <div>
                    <label class="block text-xs uppercase tracking-wider text-gray-500">Âge</label>
                    <p class="mt-1 text-lg">{{ \Carbon\Carbon::parse($beneficiaire->date_naissance)->age }} ans</p>
                </div>
            @endif
        </div>

        <div class="pt-6 border-t border-gray-200 flex items-center justify-between">
            <div class="text-sm text-gray-500">
                Créé le {{ \Carbon\Carbon::parse($beneficiaire->date_inscription ?? $beneficiaire->created_at)->format('d/m/Y H:i') }}
            </div>
            @php $roleProfil = optional(auth()->user()->profil)->role; @endphp
            @if($roleProfil !== 'benevole')
            <form action="{{ route('beneficiaire.destroy', $beneficiaire->id_beneficiaire) }}" method="POST"
                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce bénéficiaire ?');">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <i class="fas fa-trash mr-2"></i> Supprimer
                </button>
            </form>
            @endif
        </div>
    </div>
</x-app-layout>
