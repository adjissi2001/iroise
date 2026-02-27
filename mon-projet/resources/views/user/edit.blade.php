@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto">
        <h2 class="text-xl font-semibold mb-4">Modifier l'utilisateur</h2>

        <form action="{{ route('user.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-sm font-medium">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="mt-1 block w-full rounded-md border-gray-300" />
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium">Prénom</label>
                    <input type="text" name="prenom" value="{{ old('prenom', optional($user->profil)->prenom) }}" class="mt-1 block w-full rounded-md border-gray-300" />
                </div>
                <div>
                    <label class="block text-sm font-medium">Nom</label>
                    <input type="text" name="nom" value="{{ old('nom', optional($user->profil)->nom) }}" class="mt-1 block w-full rounded-md border-gray-300" />
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mt-4">
                <div>
                    <label class="block text-sm font-medium">Rôle</label>
                    <select name="role" class="mt-1 block w-full rounded-md border-gray-300">
                        <option value="" @selected(optional($user->profil)->role === null)></option>
                        <option value="referent" @selected(optional($user->profil)->role === 'referent')>Référent</option>
                        <option value="benevole" @selected(optional($user->profil)->role === 'benevole')>Bénévole</option>
                        <option value="bienfaiteur" @selected(optional($user->profil)->role === 'bienfaiteur')>Bienfaiteur</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium">Validé</label>
                    <select name="est_valide" class="mt-1 block w-full rounded-md border-gray-300">
                        <option value="1" @selected(optional($user->profil)->est_valide == 1)>Oui</option>
                        <option value="0" @selected(optional($user->profil)->est_valide == 0)>Non</option>
                    </select>
                </div>
            </div>

            <div class="mt-6 flex gap-3">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Enregistrer</button>
                <a href="{{ route('user.index') }}" class="px-4 py-2 border rounded">Annuler</a>
            </div>
        </form>
    </div>
@endsection
