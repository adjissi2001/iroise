@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/missions.css') }}">
@endpush

@section('content')
<div class="container">
    <h1>Modifier la mission</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.missions.update', ['mission' => $mission->id_mission ?? $mission->id]) }}">
        @csrf
        @method('PUT')

        <!-- 'raison' removed: category represents the raison -->

        <div class="mb-3">
            <label for="categorie" class="form-label">Catégorie</label>
            <select name="categorie" id="categorie" class="form-control {{ $errors->has('categorie') ? 'is-invalid' : '' }}" required>
                <option value="">-- sélectionner --</option>
                @foreach($categories as $c)
                    @php
                        $selected = false;
                        // compare with possible stored fields
                        if(old('categorie')) { $selected = old('categorie') == $c->id_categorie; }
                        else if(isset($mission->id_categorie)) { $selected = $mission->id_categorie == $c->id_categorie; }
                        else if(isset($mission->categorie)) { $selected = $mission->categorie == $c->nom_categorie; }
                    @endphp
                    <option value="{{ $c->id_categorie }}" {{ $selected ? 'selected' : '' }}>{{ $c->nom_categorie }}</option>
                @endforeach
            </select>
            @error('categorie')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="lieu" class="form-label">Lieu</label>
            <input type="text" name="lieu" id="lieu" class="form-control {{ $errors->has('lieu') ? 'is-invalid' : '' }}" value="{{ old('lieu', $mission->nom_lieu ?? $mission->lieu) }}" required>
            @error('lieu')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="beneficiaires" class="form-label">Bénéficiaires (un ou plusieurs)</label>
            @php
                $selectedIds = collect(old('beneficiaires', $selectedBeneficiaires ?? []))->map(fn ($v) => (int) $v)->all();
            @endphp
            <div class="beneficiaire-picker" id="beneficiairePickerEdit">
                <input type="text" id="beneficiaireSearchEdit" class="form-control {{ ($errors->has('beneficiaires') || $errors->has('beneficiaires.*')) ? 'is-invalid' : '' }}" placeholder="Rechercher par email..." autocomplete="off" />
                <div class="beneficiaire-suggestions" id="beneficiaireSuggestionsEdit" role="listbox" aria-label="Suggestions"></div>
                <div class="beneficiaire-selected" id="beneficiaireSelectedEdit"></div>
                <div class="beneficiaire-hidden-inputs" id="beneficiaireHiddenInputsEdit"></div>
                <small class="text-gray-500">Tape l'email, clique sur une suggestion pour l'ajouter. Tu peux en ajouter plusieurs.</small>
            </div>
            @error('beneficiaires')
                <div class="form-error">{{ $message }}</div>
            @enderror
            @error('beneficiaires.*')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label for="date_depart" class="form-label">Date de départ</label>
                @php
                    $dateDepartValue = old('date_depart');
                    if ($dateDepartValue === null) {
                        $rawDateDepart = $mission->date_depart ?? null;
                        $dateDepartValue = $rawDateDepart ? \Carbon\Carbon::parse($rawDateDepart)->format('Y-m-d') : '';
                    }
                @endphp
                <input type="date" name="date_depart" id="date_depart" class="form-control {{ $errors->has('date_depart') ? 'is-invalid' : '' }}" value="{{ $dateDepartValue }}" required>
                @error('date_depart')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-4 mb-3">
                <label for="heure_depart" class="form-label">Heure de départ</label>
                @php
                    $heureDepartValue = old('heure_depart');
                    if ($heureDepartValue === null) {
                        $heureDepartValue = (string) ($mission->heure_depart ?? '');
                    }
                    if (is_string($heureDepartValue) && strlen($heureDepartValue) >= 5) {
                        $heureDepartValue = substr($heureDepartValue, 0, 5);
                    }
                @endphp
                <input type="time" name="heure_depart" id="heure_depart" class="form-control {{ $errors->has('heure_depart') ? 'is-invalid' : '' }}" value="{{ $heureDepartValue }}" required>
                @error('heure_depart')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-4 mb-3">
                <label for="heure_arrivee" class="form-label">Heure d'arrivée</label>
                @php
                    $heureArriveeValue = old('heure_arrivee');
                    if ($heureArriveeValue === null) {
                        $heureArriveeValue = (string) ($mission->heure_arrivee ?? '');
                    }
                    if (is_string($heureArriveeValue) && strlen($heureArriveeValue) >= 5) {
                        $heureArriveeValue = substr($heureArriveeValue, 0, 5);
                    }
                @endphp
                <input type="time" name="heure_arrivee" id="heure_arrivee" class="form-control {{ $errors->has('heure_arrivee') ? 'is-invalid' : '' }}" value="{{ $heureArriveeValue }}" required>
                @error('heure_arrivee')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description / Remarques</label>
            <textarea name="description" id="description" class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" rows="4" required>{{ old('description', $mission->remarques ?? $mission->description) }}</textarea>
            @error('description')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="etat" class="form-label">État</label>
            <select name="etat" id="etat" class="form-control {{ $errors->has('etat') ? 'is-invalid' : '' }}" required>
                    <option value="non_prise" {{ old('etat', $mission->etat_mission ?? $mission->etat) === 'non_prise' ? 'selected' : '' }}>Non prise</option>
                    <option value="prise" {{ old('etat', $mission->etat_mission ?? $mission->etat) === 'prise' ? 'selected' : '' }}>Prise</option>
                    <option value="validee" {{ old('etat', $mission->etat_mission ?? $mission->etat) === 'validee' ? 'selected' : '' }}>Validée</option>
                    <option value="annulee" {{ old('etat', $mission->etat_mission ?? $mission->etat) === 'annulee' ? 'selected' : '' }}>Annulée</option>
            </select>
            @error('etat')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <button class="btn btn-primary">Enregistrer</button>
        <a href="{{ route('admin.missions.index') }}" class="btn btn-secondary">Annuler</a>
    </form>

    <form method="POST" action="{{ route('admin.missions.annuler', ['mission' => $mission->id_mission ?? $mission->id]) }}" class="mt-3">
        @csrf
        <button class="btn btn-danger" onclick="return confirm('Confirmer l\'annulation de la mission ?')">Annuler la mission</button>
    </form>
</div>

@push('scripts')
    <script>
        (function initBeneficiairePickerEdit() {
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

            const input = document.getElementById('beneficiaireSearchEdit');
            const suggestions = document.getElementById('beneficiaireSuggestionsEdit');
            const selectedWrap = document.getElementById('beneficiaireSelectedEdit');
            const hiddenInputs = document.getElementById('beneficiaireHiddenInputsEdit');

            if (!input || !suggestions || !selectedWrap || !hiddenInputs) return;

            const selected = new Map();
            const initialSelected = @json($selectedIds);
            const byId = new Map(allBeneficiaires.map(b => [Number(b.id), b]));

            function render() {
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
                const picker = document.getElementById('beneficiairePickerEdit');
                if (!picker) return;
                if (!picker.contains(e.target)) {
                    closeSuggestions();
                }
            });

            (initialSelected || []).forEach((id) => {
                const b = byId.get(Number(id));
                if (b) selected.set(Number(id), b);
            });
            render();
        })();
    </script>
@endpush
@endsection
