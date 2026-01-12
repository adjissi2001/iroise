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
            <select name="categorie" id="categorie" class="form-control">
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
        </div>

        <div class="mb-3">
            <label for="lieu" class="form-label">Lieu</label>
            <input type="text" name="lieu" id="lieu" class="form-control" value="{{ old('lieu', $mission->nom_lieu ?? $mission->lieu) }}">
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
                <input type="date" name="date_depart" id="date_depart" class="form-control" value="{{ $dateDepartValue }}">
            </div>
            <div class="col-md-4 mb-3">
                <label for="heure_depart" class="form-label">Heure de départ</label>
                <input type="time" name="heure_depart" id="heure_depart" class="form-control" value="{{ old('heure_depart', $mission->heure_depart) }}">
            </div>
            <div class="col-md-4 mb-3">
                <label for="heure_arrivee" class="form-label">Heure d'arrivée</label>
                <input type="time" name="heure_arrivee" id="heure_arrivee" class="form-control" value="{{ old('heure_arrivee', $mission->heure_arrivee) }}">
            </div>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description / Remarques</label>
            <textarea name="description" id="description" class="form-control" rows="4">{{ old('description', $mission->remarques ?? $mission->description) }}</textarea>
        </div>

        <div class="mb-3">
            <label for="etat" class="form-label">État</label>
            <select name="etat" id="etat" class="form-control">
                    <option value="non_prise" {{ old('etat', $mission->etat_mission ?? $mission->etat) === 'non_prise' ? 'selected' : '' }}>Non prise</option>
                    <option value="prise" {{ old('etat', $mission->etat_mission ?? $mission->etat) === 'prise' ? 'selected' : '' }}>Prise</option>
                    <option value="validee" {{ old('etat', $mission->etat_mission ?? $mission->etat) === 'validee' ? 'selected' : '' }}>Validée</option>
                    <option value="annulee" {{ old('etat', $mission->etat_mission ?? $mission->etat) === 'annulee' ? 'selected' : '' }}>Annulée</option>
            </select>
        </div>

        <button class="btn btn-primary">Enregistrer</button>
        <a href="{{ route('admin.missions.index') }}" class="btn btn-secondary">Annuler</a>
    </form>

    <form method="POST" action="{{ route('admin.missions.annuler', ['mission' => $mission->id_mission ?? $mission->id]) }}" class="mt-3">
        @csrf
        <button class="btn btn-danger" onclick="return confirm('Confirmer l\'annulation de la mission ?')">Annuler la mission</button>
    </form>
</div>
@endsection
