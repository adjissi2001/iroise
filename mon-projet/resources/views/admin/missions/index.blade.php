@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="{{ asset('css/missions.css') }}">
@endpush

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h2 class="font-semibold text-xl text-gray-800">Liste des Missions</h2>
    <button type="button" class="btn-add-mission" onclick="openCreateMissionModal()">
        <i class="fas fa-plus mr-2"></i> Ajouter une mission
    </button>
</div>

@if(session('success'))
    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
        <span class="block sm:inline"><i class="fas fa-check-circle"></i> {{ session('success') }}</span>
    </div>
@endif

<div class="overflow-x-auto">
    <table id="missionsTable" class="min-w-full">
        <thead>
            <tr>
                <th>Catégorie</th>
                <th>Lieu</th>
                <th>Date départ</th>
                <th>Heure départ</th>
                <th>Heure arrivée</th>
                <!-- <th>Kilométrage</th>-->
               <!-- <th>Remarques</th>-->
                <th>Créé par</th>
                <th>État</th>
                <th>Actions</th>
            </tr>
            <tr>
                <th><input type="text" placeholder="Catégorie" /></th>
                <th><input type="text" placeholder="Lieu" /></th>
                <th><input type="text" placeholder="Date" /></th>
                <th><input type="text" placeholder="Heure" /></th>
                <th><input type="text" placeholder="Heure" /></th>
                <!-- <th><input type="text" placeholder="Km" /></th> -->
                <!-- <th><input type="text" placeholder="Remarques" /></th>-->
                <th><input type="text" placeholder="Créateur" /></th>
                <th><input type="text" placeholder="État" /></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        @foreach($missions as $mission)
            <tr>
                <td data-label="Catégorie">{{ $mission->nom_categorie ?? ($mission->categorie ?? '-') }}</td>
                <td data-label="Lieu">{{ $mission->nom_lieu ?? ($mission->lieu ?? '-') }}</td>
                <td data-label="Date départ">{{ $mission->date_depart ?? '-' }}</td>
                <td data-label="Heure départ">{{ $mission->heure_depart ?? '-' }}</td>
                <td data-label="Heure arrivée">{{ $mission->heure_arrivee ?? '-' }}</td>
                <!-- <td data-label="Kilométrage">{{ $mission->kilometrage ?? '-' }}</td> -->
                <!-- <td data-label="Remarques">{{ $mission->remarques ?? ($mission->description ?? '-') }}</td> -->
                <td data-label="Créé par">{{ $mission->cree_par ?? ($mission->referent_id ?? '-') }}</td>
                <td data-label="État">{{ $mission->etat_mission ?? ($mission->etat ?? '-') }}</td>
                <td data-label="Actions">
                    <a href="{{ route('admin.missions.edit', ['mission' => $mission->id_mission ?? $mission->id]) }}" class="action-btn btn-edit" title="Modifier">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </a>
                    <form action="{{ route('admin.missions.annuler', ['mission' => $mission->id_mission ?? $mission->id]) }}" method="POST" style="display:inline" onsubmit="return confirm('Confirmer l\'annulation ?');">
                        @csrf
                        <button type="submit" class="action-btn btn-delete" title="Annuler">
                            <i class="fa-solid fa-ban"></i>
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<!-- Modal création mission -->
<div id="createMissionModal" class="modal" style="display:none;" aria-hidden="true">
    <div class="modal-content" role="dialog" aria-modal="true" aria-labelledby="createMissionTitle">
        <button type="button" class="modal-close" onclick="closeCreateMissionModal()" aria-label="Fermer">&times;</button>
        <h2 id="createMissionTitle" class="modal-title">Ajouter une mission</h2>

        @if ($errors->any())
            <div class="modal-alert modal-alert-error" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.missions.store') }}" class="modal-form">
            @csrf
            <input type="hidden" name="redirect_to" value="{{ url()->current() }}">

            <div class="form-group">
                <label for="categorie" class="form-label">Catégorie</label>
                <select name="categorie" id="categorie" class="form-control {{ $errors->has('categorie') ? 'is-invalid' : '' }}" required>
                    <option value="">-- sélectionner --</option>
                    @foreach(($categories ?? collect()) as $c)
                        <option value="{{ $c->id_categorie }}" {{ (string)old('categorie') === (string)$c->id_categorie ? 'selected' : '' }}>
                            {{ $c->nom_categorie }}
                        </option>
                    @endforeach
                </select>
                @error('categorie')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="lieu" class="form-label">Lieu</label>
                <input type="text" id="lieu" name="lieu" class="form-control {{ $errors->has('lieu') ? 'is-invalid' : '' }}" value="{{ old('lieu') }}" required />
                @error('lieu')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="beneficiaires" class="form-label">Bénéficiaires (un ou plusieurs)</label>
                @php
                    $oldBeneficiaires = collect(old('beneficiaires', []))->map(fn ($v) => (int) $v)->all();
                @endphp
                <div class="beneficiaire-picker" id="beneficiairePickerCreate">
                    <input type="text" id="beneficiaireSearch" class="form-control {{ ($errors->has('beneficiaires') || $errors->has('beneficiaires.*')) ? 'is-invalid' : '' }}" placeholder="Rechercher par email..." autocomplete="off" />
                    <div class="beneficiaire-suggestions" id="beneficiaireSuggestions" role="listbox" aria-label="Suggestions"></div>
                    <div class="beneficiaire-selected" id="beneficiaireSelected"></div>
                    <div class="beneficiaire-hidden-inputs" id="beneficiaireHiddenInputs"></div>
                    <small class="text-gray-500">Tape l'email, clique sur une suggestion pour l'ajouter. Tu peux en ajouter plusieurs.</small>
                </div>
                @error('beneficiaires')
                    <div class="form-error">{{ $message }}</div>
                @enderror
                @error('beneficiaires.*')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="date_depart" class="form-label">Date de départ</label>
                    <input type="date" id="date_depart" name="date_depart" class="form-control {{ $errors->has('date_depart') ? 'is-invalid' : '' }}" value="{{ old('date_depart') }}" required />
                    @error('date_depart')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="heure_depart" class="form-label">Heure départ</label>
                    @php
                        $heureDepartCreateValue = old('heure_depart', '');
                        if (is_string($heureDepartCreateValue) && strlen($heureDepartCreateValue) >= 5) {
                            $heureDepartCreateValue = substr($heureDepartCreateValue, 0, 5);
                        }
                    @endphp
                    <input type="time" id="heure_depart" name="heure_depart" class="form-control {{ $errors->has('heure_depart') ? 'is-invalid' : '' }}" value="{{ $heureDepartCreateValue }}" required />
                    @error('heure_depart')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="heure_arrivee" class="form-label">Heure arrivée</label>
                    @php
                        $heureArriveeCreateValue = old('heure_arrivee', '');
                        if (is_string($heureArriveeCreateValue) && strlen($heureArriveeCreateValue) >= 5) {
                            $heureArriveeCreateValue = substr($heureArriveeCreateValue, 0, 5);
                        }
                    @endphp
                    <input type="time" id="heure_arrivee" name="heure_arrivee" class="form-control {{ $errors->has('heure_arrivee') ? 'is-invalid' : '' }}" value="{{ $heureArriveeCreateValue }}" required />
                    @error('heure_arrivee')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="description" class="form-label">Remarques</label>
                <textarea id="description" name="description" rows="4" class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" required>{{ old('description') }}</textarea>
                @error('description')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="modal-actions">
                <button type="button" class="btn-secondary" onclick="closeCreateMissionModal()">Annuler</button>
                <button type="submit" class="btn-primary">Enregistrer</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('js/missions.js') }}"></script>
    <script>
        (function initBeneficiairePickerCreate() {
            const allBeneficiaires = [
                @foreach(($beneficiaires ?? collect()) as $b)
                    {
                        id: {{ (int)($b->id_beneficiaire ?? 0) }},
                        email: @json((string)($b->email ?? '')),
                        nom: @json((string)($b->nom ?? '')),
                        prenom: @json((string)($b->prenom ?? '')),
                    },
                @endforeach
            ];

            const input = document.getElementById('beneficiaireSearch');
            const suggestions = document.getElementById('beneficiaireSuggestions');
            const selectedWrap = document.getElementById('beneficiaireSelected');
            const hiddenInputs = document.getElementById('beneficiaireHiddenInputs');

            if (!input || !suggestions || !selectedWrap || !hiddenInputs) return;

            const selected = new Map();
            const initialSelected = @json($oldBeneficiaires);
            const byId = new Map(allBeneficiaires.map(b => [Number(b.id), b]));

            function render() {
                // chips
                selectedWrap.innerHTML = '';
                hiddenInputs.innerHTML = '';

                const ids = Array.from(selected.keys());
                if (ids.length === 0) {
                    selectedWrap.innerHTML = '<div class="beneficiaire-empty">Aucun bénéficiaire sélectionné</div>';
                    return;
                }

                ids.forEach((id) => {
                    const b = selected.get(id);
                    const labelName = [b.prenom, b.nom].filter(Boolean).join(' ').trim();
                    const label = b.email ? (labelName ? `${b.email} — ${labelName}` : b.email) : `ID ${id}`;

                    const chip = document.createElement('div');
                    chip.className = 'beneficiaire-chip';
                    chip.innerHTML = `<span class="beneficiaire-chip-text"></span><button type="button" class="beneficiaire-chip-remove" aria-label="Supprimer">×</button>`;
                    chip.querySelector('.beneficiaire-chip-text').textContent = label;
                    chip.querySelector('.beneficiaire-chip-remove').addEventListener('click', () => {
                        selected.delete(id);
                        render();
                    });
                    selectedWrap.appendChild(chip);

                    const hidden = document.createElement('input');
                    hidden.type = 'hidden';
                    hidden.name = 'beneficiaires[]';
                    hidden.value = String(id);
                    hiddenInputs.appendChild(hidden);
                });
            }

            function closeSuggestions() {
                suggestions.innerHTML = '';
                suggestions.style.display = 'none';
            }

            function openSuggestions(items) {
                suggestions.innerHTML = '';
                if (!items.length) {
                    closeSuggestions();
                    return;
                }

                items.slice(0, 8).forEach((b) => {
                    const option = document.createElement('button');
                    option.type = 'button';
                    option.className = 'beneficiaire-suggestion';
                    const labelName = [b.prenom, b.nom].filter(Boolean).join(' ').trim();
                    const label = b.email ? (labelName ? `${b.email} — ${labelName}` : b.email) : `ID ${b.id}`;
                    option.textContent = label;
                    option.addEventListener('click', () => {
                        selected.set(Number(b.id), b);
                        input.value = '';
                        closeSuggestions();
                        render();
                        input.focus();
                    });
                    suggestions.appendChild(option);
                });

                suggestions.style.display = 'block';
            }

            input.addEventListener('input', () => {
                const term = (input.value || '').toLowerCase().trim();
                if (term.length < 1) {
                    closeSuggestions();
                    return;
                }

                const matches = allBeneficiaires
                    .filter((b) => !selected.has(Number(b.id)))
                    .filter((b) => (b.email || '').toLowerCase().includes(term) || (b.nom || '').toLowerCase().includes(term) || (b.prenom || '').toLowerCase().includes(term));

                openSuggestions(matches);
            });

            input.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    closeSuggestions();
                }
            });

            document.addEventListener('click', (e) => {
                const picker = document.getElementById('beneficiairePickerCreate');
                if (!picker) return;
                if (!picker.contains(e.target)) {
                    closeSuggestions();
                }
            });

            // init old selection
            (initialSelected || []).forEach((id) => {
                const b = byId.get(Number(id));
                if (b) selected.set(Number(id), b);
            });
            render();
        })();

        function openCreateMissionModal() {
            const modal = document.getElementById('createMissionModal');
            if (!modal) return;
            modal.style.display = 'block';
            modal.setAttribute('aria-hidden', 'false');
        }

        function closeCreateMissionModal() {
            const modal = document.getElementById('createMissionModal');
            if (!modal) return;
            modal.style.display = 'none';
            modal.setAttribute('aria-hidden', 'true');
        }

        window.addEventListener('click', function (event) {
            const modal = document.getElementById('createMissionModal');
            if (modal && event.target === modal) {
                closeCreateMissionModal();
            }
        });

        window.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                closeCreateMissionModal();
            }
        });

        @if ($errors->any())
            openCreateMissionModal();
        @endif
    </script>
@endpush
@endsection
