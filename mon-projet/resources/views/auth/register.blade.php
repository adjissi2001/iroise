<x-guest-layout>
    @push('styles')
        <style>
            [x-cloak] { display: none !important; }
        </style>
    @endpush

    <h2 class="auth-title">Inscription</h2>
    <p class="auth-subtitle">Crée ton compte pour accéder à ton espace personnel.</p>

    <form method="POST" action="{{ route('register') }}" x-data="{ hasVoiture: {{ old('has_voiture', '0') == '1' ? 'true' : 'false' }} }">
        @csrf

        <div class="form-grid">
            <!-- Prénom -->
            <div>
                <x-input-label for="prenom" value="Prénom" required />
                <x-text-input id="prenom" class="block mt-1 w-full"
                    type="text"
                    name="prenom"
                    value="{{ old('prenom') }}"
                    required autofocus />
                <x-input-error :messages="$errors->get('prenom')" class="mt-2" />
            </div>

            <!-- Nom -->
            <div>
                <x-input-label for="nom" value="Nom" required />
                <x-text-input id="nom" class="block mt-1 w-full"
                    type="text"
                    name="nom"
                    value="{{ old('nom') }}"
                    required />
                <x-input-error :messages="$errors->get('nom')" class="mt-2" />
            </div>

            <!-- Date de naissance -->
            <div>
                <x-input-label for="date_naissance" value="Date de naissance" required />
                <x-text-input id="date_naissance" class="block mt-1 w-full"
                    type="date"
                    name="date_naissance"
                    value="{{ old('date_naissance') }}"
                    required />
                <x-input-error :messages="$errors->get('date_naissance')" class="mt-2" />
            </div>

            <!-- Téléphone -->
            <div>
                <x-input-label for="num_tel" value="Téléphone" required />
                <x-text-input id="num_tel" class="block mt-1 w-full"
                    type="text"
                    name="num_tel"
                    value="{{ old('num_tel') }}" required />
                <x-input-error :messages="$errors->get('num_tel')" class="mt-2" />
            </div>

            <!-- Adresse -->
            <div>
                <x-input-label for="adresse" value="Adresse" required />
                <x-text-input id="adresse" class="block mt-1 w-full"
                    type="text"
                    name="adresse"
                    value="{{ old('adresse') }}"
                    required autocomplete="address-line1" />
                <x-input-error :messages="$errors->get('adresse')" class="mt-2" />
            </div>

            <!-- Ville -->
            <div>
                <x-input-label for="ville" value="Ville" required />
                <x-text-input id="ville" class="block mt-1 w-full"
                    type="text"
                    name="ville"
                    value="{{ old('ville') }}"
                    required autocomplete="address-level2" />
                <x-input-error :messages="$errors->get('ville')" class="mt-2" />
            </div>

            <!-- Code postal -->
            <div>
                <x-input-label for="code_postale" value="Code postal" required />
                <x-text-input id="code_postale" class="block mt-1 w-full"
                    type="text"
                    name="code_postale"
                    value="{{ old('code_postale') }}"
                    required autocomplete="postal-code" />
                <x-input-error :messages="$errors->get('code_postale')" class="mt-2" />
            </div>

            <!-- Numéro fixe -->
            <div>
                <x-input-label for="num_fixe" value="Numéro fixe" />
                <x-text-input id="num_fixe" class="block mt-1 w-full"
                    type="text"
                    name="num_fixe"
                    value="{{ old('num_fixe') }}" autocomplete="tel" />
                <x-input-error :messages="$errors->get('num_fixe')" class="mt-2" />
            </div>

            <!-- Rôle métier -->
            <div>
                <x-input-label for="role_profil" value="Rôle" required />
                <select id="role_profil" name="role_profil" class="block mt-1 w-full">
                    <option value="benevole" @selected(old('role_profil') === 'benevole')>Bénévole</option>
                    <option value="referent" @selected(old('role_profil') === 'referent')>Référent</option>
                    <option value="bienfaiteur" @selected(old('role_profil') === 'bienfaiteur')>Bienfaiteur</option>
                </select>
                <x-input-error :messages="$errors->get('role_profil')" class="mt-2" />
            </div>

            <!-- Voiture -->
            <div style="grid-column: 1 / -1;">
                <x-input-label for="has_voiture" value="As-tu une voiture ?" />
                <input type="hidden" name="has_voiture" value="0">
                <label class="flex items-center gap-2 mt-2">
                    <input id="has_voiture"
                        type="checkbox"
                        name="has_voiture"
                        value="1"
                        x-model="hasVoiture"
                        class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500" />
                    <span>Oui, j'ai une voiture</span>
                </label>
                <x-input-error :messages="$errors->get('has_voiture')" class="mt-2" />
            </div>

            <div x-cloak x-show="hasVoiture" x-transition>
                <x-input-label for="num_immatriculation" value="Numéro d'immatriculation" required />
                <x-text-input id="num_immatriculation" class="block mt-1 w-full"
                    type="text"
                    name="num_immatriculation"
                    value="{{ old('num_immatriculation') }}"
                    placeholder="Ex : AB-123-CD"
                    pattern="[A-Za-z]{2}-[0-9]{3}-[A-Za-z]{2}"
                    title="Format attendu : AB-123-CD"
                    x-bind:required="hasVoiture" />
                <x-input-error :messages="$errors->get('num_immatriculation')" class="mt-2" />
            </div>

            <div x-cloak x-show="hasVoiture" x-transition>
                <x-input-label for="puissance_voiture" value="Puissance" required />
                <x-text-input id="puissance_voiture" class="block mt-1 w-full"
                    type="number"
                    name="puissance_voiture"
                    value="{{ old('puissance_voiture') }}"
                    min="1"
                    step="1"
                    x-bind:required="hasVoiture" />
                <x-input-error :messages="$errors->get('puissance_voiture')" class="mt-2" />
            </div>

            <!-- Email -->
            <div>
                <x-input-label for="email" value="Email" required />
                <x-text-input id="email" class="block mt-1 w-full"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Mot de passe -->
            <div>
                <x-input-label for="password" value="Mot de passe" required />
                <x-text-input id="password" class="block mt-1 w-full"
                    type="password"
                    name="password"
                    required />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirmation -->
            <div>
                <x-input-label for="password_confirmation" value="Confirmer le mot de passe" required />
                <x-text-input id="password_confirmation" class="block mt-1 w-full"
                    type="password"
                    name="password_confirmation"
                    required />
            </div>
        </div>

        <div class="form-actions">
            <a class="btn btn-login" href="{{ route('login') }}">Déjà inscrit ?</a>
            <button type="submit" class="btn btn-register">S'inscrire</button>
        </div>
    </form>
</x-guest-layout>
