@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-xl shadow-md p-8 mt-10">
    <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Modifier le bénéficiaire</h2>
    @if ($errors->any())
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form method="POST" action="{{ route('beneficiaire.update', $beneficiaire->id_beneficiaire) }}">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700">Nom</label>
                <input type="text" name="nom" value="{{ old('nom', $beneficiaire->nom) }}" required class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-600 focus:ring-blue-600">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Prénom</label>
                <input type="text" name="prenom" value="{{ old('prenom', $beneficiaire->prenom) }}" required class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-600 focus:ring-blue-600">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Date de naissance</label>
                <input type="date" name="date_naissance" value="{{ old('date_naissance', $beneficiaire->date_naissance) }}" required class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-600 focus:ring-blue-600">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" value="{{ old('email', $beneficiaire->email) }}" class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-600 focus:ring-blue-600">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Téléphone mobile</label>
                <input type="text" name="num_tel" value="{{ old('num_tel', $beneficiaire->num_tel) }}" class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-600 focus:ring-blue-600">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Téléphone fixe</label>
                <input type="text" name="num_fixe" value="{{ old('num_fixe', $beneficiaire->num_fixe) }}" class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-600 focus:ring-blue-600">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Code postal</label>
                <input type="text" name="code_postal" value="{{ old('code_postal', $beneficiaire->code_postal) }}" class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-600 focus:ring-blue-600">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Adresse</label>
                <input type="text" name="adresse" value="{{ old('adresse', $beneficiaire->adresse) }}" class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-600 focus:ring-blue-600">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Commune</label>
                <input type="text" name="commune" value="{{ old('commune', $beneficiaire->commune) }}" class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-600 focus:ring-blue-600">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Contact urgence</label>
                <input type="text" name="contact_urgence" value="{{ old('contact_urgence', $beneficiaire->contact_urgence) }}" class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-600 focus:ring-blue-600">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Téléphone contact urgence</label>
                <input type="text" name="tel_contact_urgence" value="{{ old('tel_contact_urgence', $beneficiaire->tel_contact_urgence) }}" class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-600 focus:ring-blue-600">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Montant cotisation</label>
                <input type="number" name="montant_cotisation" min="0" step="0.01" value="{{ old('montant_cotisation', $beneficiaire->montant_cotisation) }}" class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-600 focus:ring-blue-600">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Moyen de paiement</label>
                <select name="moyen_paiement" class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-600 focus:ring-blue-600">
                    <option value="">-- Sélectionnez --</option>
                    <option value="especes" {{ old('moyen_paiement', $beneficiaire->moyen_paiement) == 'especes' ? 'selected' : '' }}>Espèces</option>
                    <option value="cheque" {{ old('moyen_paiement', $beneficiaire->moyen_paiement) == 'cheque' ? 'selected' : '' }}>Chèque</option>
                    <option value="virement" {{ old('moyen_paiement', $beneficiaire->moyen_paiement) == 'virement' ? 'selected' : '' }}>Virement</option>
                    <option value="liquide" {{ old('moyen_paiement', $beneficiaire->moyen_paiement) == 'liquide' ? 'selected' : '' }}>Liquide</option>
                </select>
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700">Remarques</label>
                <textarea name="remarques" rows="2" class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-600 focus:ring-blue-600">{{ old('remarques', $beneficiaire->remarques) }}</textarea>
            </div>
        </div>
       <div class="mt-8 flex justify-between">
            <a href="{{ route('beneficiaire.index') }}" class="inline-flex items-center px-5 py-2.5 bg-gray-100 text-gray-700 rounded-xl border border-gray-300 hover:bg-gray-200 transition font-medium">
                <i class="fas fa-arrow-left mr-2"></i> Annuler
            </a>
            <button type="submit" class="inline-flex items-center px-5 py-2.5 bg-gray-100 text-gray-700 rounded-xl border border-gray-300 hover:bg-gray-200 transition font-medium">
                <i class="fas fa-save mr-2"></i> Enregistrer
            </button>
        </div>
    </form>
</div>
@endsection
