<div id="createUserModal" class="modal" style="display:none;" aria-hidden="true">
    <style>
        /* Overlay / container */
        #createUserModal {
            position: fixed;
            inset: 0;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 3.5rem 1rem;
            background: rgba(0,0,0,0.32);
            backdrop-filter: blur(4px);
            z-index: 60;
        }

        /* Card */
        #createUserModal .modal-content {
            background: rgba(243,244,246,0.98); /* light gray */
            border-radius: 12px;
            box-shadow: 0 20px 40px rgba(15,23,42,0.25);
            padding: 2rem 2.25rem;
            max-width: 920px !important;
            width: 86vw !important;
            max-height: 80vh !important;
            overflow-y: auto !important;
            position: relative;
            transform: translateY(-6vh);
            border: 1px solid rgba(15,23,42,0.04);
            -webkit-backdrop-filter: none;
            backdrop-filter: none;
        }

        /* Title / subtitle */
        #createUserModal .modal-title {
            font-size: 1.75rem;
            font-weight: 800;
            text-align: center;
            color: #0f172a;
            margin: 0 0 0.25rem 0;
        }

        #createUserModal .modal-content > p {
            text-align: center;
            color: #6b7280;
            margin-bottom: 1rem;
        }

        /* Labels */
        #createUserModal .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 700 !important;
            font-size: 1.05rem !important;
            color: #111827;
        }

        /* Inputs / selects: stronger border and clearer appearance */
        #createUserModal .form-control,
        #createUserModal input[type="text"],
        #createUserModal input[type="email"],
        #createUserModal input[type="password"],
        #createUserModal input[type="date"],
        #createUserModal input[type="number"],
        #createUserModal select,
        #createUserModal textarea {
            width: 100%;
            padding: 0.7rem 0.9rem;
            border-radius: 8px;
            border: 1px solid rgba(15,23,42,0.12);
            background: #ffffff;
            box-shadow: 0 1px 2px rgba(2,6,23,0.04);
            font-size: 0.95rem;
            color: #0f172a;
            -webkit-backface-visibility: hidden;
            backface-visibility: hidden;
        }

        /* Input focus state */
        #createUserModal .form-control:focus,
        #createUserModal input:focus,
        #createUserModal select:focus,
        #createUserModal textarea:focus {
            outline: none;
            box-shadow: 0 0 0 4px rgba(11,99,255,0.06);
            border-color: rgba(11,99,255,0.35);
        }

        /* Checkbox label small tweak */
        #createUserModal .form-group label.flex { font-weight: 600; font-size: 0.95rem; }

        /* Make checkboxes clearly visible */
        #createUserModal input[type="checkbox"] {
            width: 18px;
            height: 18px;
            -webkit-appearance: none;
            appearance: none;
            border-radius: 4px;
            border: 1px solid #cbd5e1;
            background: #fff;
            display: inline-block;
            vertical-align: middle;
            position: relative;
            margin-right: 8px;
            cursor: pointer;
        }

        #createUserModal input[type="checkbox"]:checked::after {
            content: '\2713';
            font-size: 12px;
            color: #0b63ff;
            position: absolute;
            left: 3px;
            top: -1px;
        }

        /* Reduce vertical spacing to keep modal shorter */
        #createUserModal .form-group { margin-bottom: 0.75rem !important; }

        /* Actions */
        #createUserModal .modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 0.75rem;
            padding-top: 0.5rem;
        }

        #createUserModal .btn-primary {
            background: linear-gradient(180deg,#0b63ff,#084fcf);
            color: #fff;
            border: none;
            padding: 0.6rem 1rem;
            border-radius: 8px;
            font-weight: 700;
        }

        #createUserModal .btn-secondary {
            background: transparent;
            border: 1px solid rgba(15,23,42,0.08);
            color: #0f172a;
            padding: 0.55rem 0.9rem;
            border-radius: 8px;
            font-weight: 600;
        }

        /* Close button */
        #createUserModal .modal-close {
            position: absolute;
            right: 14px;
            top: 12px;
            background: transparent;
            border: none;
            font-size: 1.5rem;
            line-height: 1;
            color: rgba(15,23,42,0.6);
            cursor: pointer;
        }

        /* Grid spacing a bit larger like the image */
        #createUserModal .grid { gap: 1.25rem !important; }
    </style>
    <div class="modal-content" role="dialog" aria-modal="true" aria-labelledby="createUserTitle">
        <button type="button" class="modal-close" onclick="closeCreateUserModal()" aria-label="Fermer">&times;</button>
        <h2 id="createUserTitle" class="modal-title">Inscription</h2>
        <p class="text-sm text-gray-600 mb-4">Crée ton compte pour accéder à ton espace personnel.</p>

        @if ($errors->any())
            <div class="modal-alert modal-alert-error" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" class="modal-form" x-data="{ hasVoiture: {{ old('has_voiture', '0') == '1' ? 'true' : 'false' }} }">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="form-group">
                    <label for="prenom" class="form-label">Prénom *</label>
                    <x-text-input id="prenom" class="form-control" type="text" name="prenom" value="{{ old('prenom') }}" required autofocus />
                    <x-input-error :messages="$errors->get('prenom')" class="mt-2" />
                </div>

                <div class="form-group">
                    <label for="nom" class="form-label">Nom *</label>
                    <x-text-input id="nom" class="form-control" type="text" name="nom" value="{{ old('nom') }}" required />
                    <x-input-error :messages="$errors->get('nom')" class="mt-2" />
                </div>

                <div class="form-group">
                    <label for="date_naissance" class="form-label">Date de naissance *</label>
                    <x-text-input id="date_naissance" class="form-control" type="date" name="date_naissance" value="{{ old('date_naissance') }}" required />
                    <x-input-error :messages="$errors->get('date_naissance')" class="mt-2" />
                </div>

                <div class="form-group">
                    <label for="num_tel" class="form-label">Téléphone *</label>
                    <x-text-input id="num_tel" class="form-control" type="text" name="num_tel" value="{{ old('num_tel') }}" />
                    <x-input-error :messages="$errors->get('num_tel')" class="mt-2" />
                </div>

                <div class="form-group">
                    <label for="adresse" class="form-label">Adresse *</label>
                    <x-text-input id="adresse" class="form-control" type="text" name="adresse" value="{{ old('adresse') }}" />
                    <x-input-error :messages="$errors->get('adresse')" class="mt-2" />
                </div>

                <div class="form-group">
                    <label for="ville" class="form-label">Ville *</label>
                    <x-text-input id="ville" class="form-control" type="text" name="ville" value="{{ old('ville') }}" />
                    <x-input-error :messages="$errors->get('ville')" class="mt-2" />
                </div>

                <div class="form-group">
                    <label for="code_postale" class="form-label">Code postal *</label>
                    <x-text-input id="code_postale" class="form-control" type="text" name="code_postale" value="{{ old('code_postale') }}" />
                    <x-input-error :messages="$errors->get('code_postale')" class="mt-2" />
                </div>

                <div class="form-group">
                    <label for="num_fixe" class="form-label">Numéro fixe</label>
                    <x-text-input id="num_fixe" class="form-control" type="text" name="num_fixe" value="{{ old('num_fixe') }}" />
                    <x-input-error :messages="$errors->get('num_fixe')" class="mt-2" />
                </div>

                <div class="form-group">
                    <label for="role_profil" class="form-label">Rôle *</label>
                    <select id="role_profil" name="role_profil" class="form-control">
                        <option value="benevole" @selected(old('role_profil') === 'benevole')>Bénévole</option>
                        <option value="referent" @selected(old('role_profil') === 'referent')>Référent</option>
                        <option value="bienfaiteur" @selected(old('role_profil') === 'bienfaiteur')>Bienfaiteur</option>
                    </select>
                    <x-input-error :messages="$errors->get('role_profil')" class="mt-2" />
                </div>

                <div class="form-group">
                    <label class="form-label">As-tu une voiture ?</label>
                    <input type="hidden" name="has_voiture" value="0">
                    <label class="flex items-center gap-2">
                        <input id="has_voiture" type="checkbox" name="has_voiture" value="1" x-model="hasVoiture" class="form-control" style="width:18px;height:18px;padding:0;margin-right:6px;" />
                        <span>Oui, j'ai une voiture</span>
                    </label>
                </div>

                <div x-cloak x-show="hasVoiture" class="form-group">
                    <label for="num_immatriculation" class="form-label">Numéro d'immatriculation</label>
                    <x-text-input id="num_immatriculation" class="form-control" type="text" name="num_immatriculation" value="{{ old('num_immatriculation') }}" />
                    <x-input-error :messages="$errors->get('num_immatriculation')" class="mt-2" />
                </div>

                <div x-cloak x-show="hasVoiture" class="form-group">
                    <label for="puissance_voiture" class="form-label">Puissance</label>
                    <x-text-input id="puissance_voiture" class="form-control" type="number" name="puissance_voiture" value="{{ old('puissance_voiture') }}" min="1" />
                    <x-input-error :messages="$errors->get('puissance_voiture')" class="mt-2" />
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">Email *</label>
                    <x-text-input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" required />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Mot de passe *</label>
                    <x-text-input id="password" class="form-control" type="password" name="password" required />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div class="md:col-span-2 form-group">
                    <label for="password_confirmation" class="form-label">Confirmer le mot de passe *</label>
                    <x-text-input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required />
                </div>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn-secondary" onclick="closeCreateUserModal()">Annuler</button>
                <button type="submit" class="btn-primary">Enregistrer</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
    <script>
        function openCreateUserModal() {
            const modal = document.getElementById('createUserModal');
            if (!modal) return;
            modal.style.display = 'block';
            modal.setAttribute('aria-hidden', 'false');
        }

        function closeCreateUserModal() {
            const modal = document.getElementById('createUserModal');
            if (!modal) return;
            modal.style.display = 'none';
            modal.setAttribute('aria-hidden', 'true');
        }

        window.addEventListener('click', function (event) {
            const modal = document.getElementById('createUserModal');
            if (modal && event.target === modal) {
                closeCreateUserModal();
            }
        });

        window.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                closeCreateUserModal();
            }
        });

        @if ($errors->any())
            openCreateUserModal();
        @endif
    </script>
@endpush
