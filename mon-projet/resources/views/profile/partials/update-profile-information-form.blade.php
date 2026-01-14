<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="email" :value="__('Email')" required />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div>
            <x-input-label for="prenom" value="Prénom" required />
            <x-text-input id="prenom" name="prenom" type="text" class="mt-1 block w-full" :value="old('prenom', optional($user->profil)->prenom)" required autocomplete="given-name" />
            <x-input-error class="mt-2" :messages="$errors->get('prenom')" />
        </div>

        <div>
            <x-input-label for="nom" value="Nom" required />
            <x-text-input id="nom" name="nom" type="text" class="mt-1 block w-full" :value="old('nom', optional($user->profil)->nom)" required autocomplete="family-name" />
            <x-input-error class="mt-2" :messages="$errors->get('nom')" />
        </div>

        <div>
            <x-input-label for="date_naissance" value="Date de naissance" required />
            <x-text-input id="date_naissance" name="date_naissance" type="date" class="mt-1 block w-full" :value="old('date_naissance', optional($user->profil)->date_naissance)" required />
            <x-input-error class="mt-2" :messages="$errors->get('date_naissance')" />
        </div>

        <div>
            <x-input-label for="num_tel" value="Téléphone" />
            <x-text-input id="num_tel" name="num_tel" type="text" class="mt-1 block w-full" :value="old('num_tel', optional($user->profil)->num_tel)" autocomplete="tel" />
            <x-input-error class="mt-2" :messages="$errors->get('num_tel')" />
        </div>

        <div>
            <x-input-label for="adresse" value="Adresse" required />
            <x-text-input id="adresse" name="adresse" type="text" class="mt-1 block w-full" :value="old('adresse', optional($user->profil)->adresse)" required autocomplete="address-line1" />
            <x-input-error class="mt-2" :messages="$errors->get('adresse')" />
        </div>

        <div>
            <x-input-label for="ville" value="Ville" required />
            <x-text-input id="ville" name="ville" type="text" class="mt-1 block w-full" :value="old('ville', optional($user->profil)->ville)" required autocomplete="address-level2" />
            <x-input-error class="mt-2" :messages="$errors->get('ville')" />
        </div>

        <div>
            <x-input-label for="code_postale" value="Code postal" required />
            <x-text-input id="code_postale" name="code_postale" type="text" class="mt-1 block w-full" :value="old('code_postale', optional($user->profil)->code_postale)" required autocomplete="postal-code" />
            <x-input-error class="mt-2" :messages="$errors->get('code_postale')" />
        </div>

        <div>
            <x-input-label for="num_fixe" value="Numéro fixe" />
            <x-text-input id="num_fixe" name="num_fixe" type="text" class="mt-1 block w-full" :value="old('num_fixe', optional($user->profil)->num_fixe)" autocomplete="tel" />
            <x-input-error class="mt-2" :messages="$errors->get('num_fixe')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
