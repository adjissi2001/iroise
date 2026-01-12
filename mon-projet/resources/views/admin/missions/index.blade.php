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
                <select name="categorie" id="categorie" class="form-control">
                    <option value="">-- sélectionner --</option>
                    @foreach(($categories ?? collect()) as $c)
                        <option value="{{ $c->id_categorie }}" {{ (string)old('categorie') === (string)$c->id_categorie ? 'selected' : '' }}>
                            {{ $c->nom_categorie }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="lieu" class="form-label">Lieu</label>
                <input type="text" id="lieu" name="lieu" class="form-control" value="{{ old('lieu') }}" />
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="date_depart" class="form-label">Date de départ</label>
                    <input type="date" id="date_depart" name="date_depart" class="form-control" value="{{ old('date_depart') }}" />
                </div>
                <div class="form-group">
                    <label for="heure_depart" class="form-label">Heure départ</label>
                    <input type="time" id="heure_depart" name="heure_depart" class="form-control" value="{{ old('heure_depart') }}" />
                </div>
                <div class="form-group">
                    <label for="heure_arrivee" class="form-label">Heure arrivée</label>
                    <input type="time" id="heure_arrivee" name="heure_arrivee" class="form-control" value="{{ old('heure_arrivee') }}" />
                </div>
            </div>

            <div class="form-group">
                <label for="description" class="form-label">Remarques</label>
                <textarea id="description" name="description" rows="4" class="form-control">{{ old('description') }}</textarea>
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
