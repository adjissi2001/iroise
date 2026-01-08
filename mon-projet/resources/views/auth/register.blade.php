<x-guest-layout>
    <h2 class="auth-title">Inscription</h2>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Prénom -->
        <div>
            <x-input-label for="prenom" value="Prénom" />
            <x-text-input id="prenom" class="block mt-1 w-full"
                type="text"
                name="prenom"
                value="{{ old('prenom') }}"
                required autofocus />
            <x-input-error :messages="$errors->get('prenom')" class="mt-2" />
        </div>

        <!-- Nom -->
        <div class="mt-4">
            <x-input-label for="nom" value="Nom" />
            <x-text-input id="nom" class="block mt-1 w-full"
                type="text"
                name="nom"
                value="{{ old('nom') }}"
                required />
            <x-input-error :messages="$errors->get('nom')" class="mt-2" />
        </div>

        <!-- Date de naissance -->
        <div class="mt-4">
            <x-input-label for="date_naissance" value="Date de naissance" />
            <x-text-input id="date_naissance" class="block mt-1 w-full"
                type="date"
                name="date_naissance"
                value="{{ old('date_naissance') }}"
                required />
            <x-input-error :messages="$errors->get('date_naissance')" class="mt-2" />
        </div>

        <!-- Téléphone -->
        <div class="mt-4">
            <x-input-label for="num_tel" value="Téléphone" />
            <x-text-input id="num_tel" class="block mt-1 w-full"
                type="text"
                name="num_tel"
                value="{{ old('num_tel') }}" />
            <x-input-error :messages="$errors->get('num_tel')" class="mt-2" />
        </div>

        <!-- Rôle métier -->
        <div class="mt-4">
            <x-input-label for="role_profil" value="Rôle" />
            <select id="role_profil" name="role_profil"
                class="block mt-1 w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                <option value="benevole">Bénévole</option>
                <option value="referent">Référent</option>
                <option value="bienfaiteur">Bienfaiteur</option>
            </select>
            <x-input-error :messages="$errors->get('role_profil')" class="mt-2" />
        </div>

        <!-- Email -->
        <div class="mt-4">
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" class="block mt-1 w-full"
                type="email"
                name="email"
                value="{{ old('email') }}"
                required />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Mot de passe -->
        <div class="mt-4">
            <x-input-label for="password" value="Mot de passe" />
            <x-text-input id="password" class="block mt-1 w-full"
                type="password"
                name="password"
                required />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirmation -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" value="Confirmer le mot de passe" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                type="password"
                name="password_confirmation"
                required />
        </div>

        <div class="flex items-center justify-end mt-6">
            <a class="underline text-sm text-gray-600 hover:text-gray-900"
               href="{{ route('login') }}">
                Déjà inscrit ?
            </a>

            <x-primary-button class="ms-4">
                S'inscrire
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
