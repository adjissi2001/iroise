@extends('layouts.app')

@section('content')
    <div class="max-w-5xl mx-auto">
        <div class="mb-6">
            <h2 class="font-semibold text-2xl text-gray-800">
                {{ __('Ajouter un Bénéficiaire') }}
            </h2>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6">
            <form action="{{ route('beneficiaire.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nom -->
                    <div>
                        <label for="nom" class="block font-medium text-sm text-gray-700">
                            {{ __('Nom') }} <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="nom" 
                            name="nom" 
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('nom') border-red-500 @enderror"
                            value="{{ old('nom') }}"
                            placeholder="Nom du bénéficiaire"
                            required
                        >
                        @error('nom')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Prénom -->
                    <div>
                        <label for="prenom" class="block font-medium text-sm text-gray-700">
                            {{ __('Prénom') }} <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="prenom" 
                            name="prenom" 
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('prenom') border-red-500 @enderror"
                            value="{{ old('prenom') }}"
                            placeholder="Prénom du bénéficiaire"
                            required
                        >
                        @error('prenom')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Date de Naissance -->
                    <div>
                        <label for="date_naissance" class="block font-medium text-sm text-gray-700">
                            {{ __('Date de Naissance') }} <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="date" 
                            id="date_naissance" 
                            name="date_naissance" 
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('date_naissance') border-red-500 @enderror"
                            value="{{ old('date_naissance') }}"
                            required
                        >
                        @error('date_naissance')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block font-medium text-sm text-gray-700">
                            {{ __('Email') }}
                        </label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('email') border-red-500 @enderror"
                            value="{{ old('email') }}"
                            placeholder="email@example.com"
                        >
                        @error('email')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Numéro de Téléphone Mobile -->
                    <div>
                        <label for="num_tel" class="block font-medium text-sm text-gray-700">
                            {{ __('Téléphone Mobile') }}
                        </label>
                        <input 
                            type="text" 
                            id="num_tel" 
                            name="num_tel" 
                            maxlength="10"
                            pattern="[0-9]{10}"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('num_tel') border-red-500 @enderror"
                            value="{{ old('num_tel') }}"
                            placeholder="Téléphone mobile"
                        >
                        @error('num_tel')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Numéro de Téléphone Fixe -->
                    <div>
                        <label for="num_fixe" class="block font-medium text-sm text-gray-700">
                            {{ __('Téléphone Fixe') }}
                        </label>
                        <input 
                            type="text" 
                            id="num_fixe" 
                            name="num_fixe" 
                            maxlength="10"
                            pattern="[0-9]{10}"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('num_fixe') border-red-500 @enderror"
                            value="{{ old('num_fixe') }}"
                            placeholder="Téléphone fixe"
                        >
                        @error('num_fixe')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Contact Urgence -->
                    <div>
                        <label for="contact_urgence" class="block font-medium text-sm text-gray-700">
                            {{ __('Contact Urgence') }}
                        </label>
                        <input 
                            type="text" 
                            id="contact_urgence" 
                            name="contact_urgence" 
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('contact_urgence') border-red-500 @enderror"
                            value="{{ old('contact_urgence') }}"
                            placeholder="Nom du contact d'urgence"
                        >
                        @error('contact_urgence')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Téléphone Contact Urgence -->
                    <div>
                        <label for="tel_contact_urgence" class="block font-medium text-sm text-gray-700">
                            {{ __('Téléphone Contact Urgence') }}
                        </label>
                       <input 
                            type="text" 
                            id="tel_contact_urgence" 
                            name="tel_contact_urgence"
                            maxlength="10"
                            pattern="[0-9]{10}"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('tel_contact_urgence') border-red-500 @enderror"
                            value="{{ old('tel_contact_urgence') }}"
                            placeholder="Téléphone du contact d'urgence"
                            required
                        >
                        @error('tel_contact_urgence')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Montant Cotisation -->
                    <div>
                        <label for="montant_cotisation" class="block font-medium text-sm text-gray-700">
                            {{ __('Montant Cotisation') }}
                        </label>
                        <input 
                            type="number" 
                            id="montant_cotisation" 
                            name="montant_cotisation" 
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('montant_cotisation') border-red-500 @enderror"
                            value="{{ old('montant_cotisation') }}"
                            placeholder="Montant cotisation"
                            min="0"
                            step="0.01"
                        >
                        @error('montant_cotisation')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Moyen de Paiement -->
                    <div>
                        <label for="moyen_paiement" class="block font-medium text-sm text-gray-700">
                            {{ __('Moyen de Paiement') }}
                        </label>
                        <select 
                            id="moyen_paiement" 
                            name="moyen_paiement" 
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('moyen_paiement') border-red-500 @enderror"
                        >
                            <option value="">-- Sélectionnez --</option>
                            <option value="especes" {{ old('moyen_paiement') == 'especes' ? 'selected' : '' }}>Espèces</option>
                            <option value="cheque" {{ old('moyen_paiement') == 'cheque' ? 'selected' : '' }}>Chèque</option>
                            <option value="virement" {{ old('moyen_paiement') == 'virement' ? 'selected' : '' }}>Virement</option>
                            <option value="liquide" {{ old('moyen_paiement') == 'liquide' ? 'selected' : '' }}>Liquide</option>
                        </select>
                        @error('moyen_paiement')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <!-- Code postal -->
                <div>
                    <label for="code_postal" class="block font-medium text-sm text-gray-700">Code postal</label>
                   <input 
                        type="text" 
                        id="code_postal" 
                        name="code_postal"
                        maxlength="5"
                        pattern="[0-9]{5}"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('code_postal') border-red-500 @enderror"
                        value="{{ old('code_postal') }}"
                        placeholder="Code postal"
                    >
                    @error('code_postal')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <!-- Adresse -->
                <div>
                    <label for="adresse" class="block font-medium text-sm text-gray-700">Adresse</label>
                    <input 
                        type="text" 
                        id="adresse" 
                        name="adresse" 
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('adresse') border-red-500 @enderror"
                        value="{{ old('adresse') }}"
                        placeholder="Adresse du bénéficiaire"
                    >
                    @error('adresse')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <!-- Commune -->
                <div>
                    <label for="commune" class="block font-medium text-sm text-gray-700">Commune</label>
                    <input 
                        type="text" 
                        id="commune" 
                        name="commune" 
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('commune') border-red-500 @enderror"
                        value="{{ old('commune') }}"
                        placeholder="Commune du bénéficiaire"
                    >
                    @error('commune')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <!-- Remarques -->
                <div class="md:col-span-2">
                    <label for="remarques" class="block font-medium text-sm text-gray-700">Remarques</label>
                    <textarea 
                        id="remarques" 
                        name="remarques" 
                        rows="2"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('remarques') border-red-500 @enderror"
                        placeholder="Remarques éventuelles..."
                    >{{ old('remarques') }}</textarea>
                    @error('remarques')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Boutons d'action -->
                <div class="flex justify-end gap-4 mt-8">
                    <a href="{{ route('beneficiaire.index') }}" class="inline-flex items-center px-5 py-2.5 bg-gray-100 text-gray-700 rounded-xl border border-gray-300 hover:bg-gray-200 transition font-medium">
                        {{ __('Annuler') }}
                    </a>
                    <button 
                        type="submit" 
                        class="inline-flex items-center px-5 py-2.5 bg-gray-100 text-gray-700 rounded-xl border border-gray-300 hover:bg-gray-200 transition font-medium"
                    >
                        <i class="fas fa-save mr-2"></i> {{ __('Ajouter') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
