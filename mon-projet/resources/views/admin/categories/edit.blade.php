@extends('layouts.app')

@section('content')
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h2 class="font-semibold text-xl text-gray-800">Modifier une catégorie</h2>
            <p class="text-sm text-gray-500">ID: {{ $categorie->id_categorie }}</p>
        </div>

        <a
            href="{{ route('admin.categories.index') }}"
            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50"
        >
            Retour
        </a>
    </div>

    @if(session('error'))
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded" role="alert">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="max-w-3xl space-y-6">
        <div class="bg-white rounded-lg border border-gray-200 p-5">
            <form id="categorieUpdateForm" method="POST" action="{{ route('admin.categories.update', ['id' => $categorie->id_categorie]) }}" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label for="nom_categorie" class="block text-sm font-medium text-gray-700">Nom</label>
                    <input
                        id="nom_categorie"
                        name="nom_categorie"
                        type="text"
                        value="{{ old('nom_categorie', $categorie->nom_categorie ?? '') }}"
                        required
                        class="mt-1 block w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                    />
                </div>

                @if(!empty($hasDescription))
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Description (optionnel)</label>
                        <textarea
                            id="description"
                            name="description"
                            rows="3"
                            class="mt-1 block w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        >{{ old('description', $categorie->description ?? '') }}</textarea>
                    </div>
                @endif
            </form>

            <div class="mt-6 flex items-center gap-3">
                <x-primary-button type="submit" form="categorieUpdateForm">
                    Mettre à jour
                </x-primary-button>

                <form
                    action="{{ route('admin.categories.destroy', ['id' => $categorie->id_categorie]) }}"
                    method="POST"
                    onsubmit="return confirm('Supprimer cette catégorie ?');"
                >
                    @csrf
                    @method('DELETE')
                    <button
                        type="submit"
                        class="inline-flex items-center px-4 py-2 border border-red-200 rounded-md font-semibold text-xs text-red-700 uppercase tracking-widest hover:bg-red-50"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="w-5 h-5 mr-2">
                            <path d="M5.5 5.5A.5.5 0 0 1 6 6v8a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0A.5.5 0 0 1 8 6v8a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v8a.5.5 0 0 0 1 0V6z"/>
                            <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1 0-2H5.5a1 1 0 0 1 1-1h3a1 1 0 0 1 1 1H14a1 1 0 0 1 .5 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3h11-11z"/>
                        </svg>
                        Supprimer
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
