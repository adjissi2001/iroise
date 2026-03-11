@extends('layouts.app')

@section('content')
    <div class="mb-6 flex flex-col gap-1 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h2 class="font-semibold text-xl text-gray-800">Catégories de mission</h2>
            <p class="text-sm text-gray-600">Créer, modifier et supprimer les catégories utilisées pour les missions.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

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

    <div class="mb-8 bg-white rounded-lg border border-gray-200 p-5">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h3 class="font-semibold text-lg text-gray-800">Ajouter une catégorie</h3>
                <p class="mt-1 text-sm text-gray-600">Le nom est obligatoire. La description est optionnelle.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.categories.store') }}" class="mt-4 space-y-4">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="nom_categorie" class="block text-sm font-medium text-gray-700">Nom <span class="text-red-500">*</span></label>
                    <input
                        id="nom_categorie"
                        name="nom_categorie"
                        type="text"
                        value="{{ old('nom_categorie') }}"
                        required
                        class="mt-1 block w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                    />
                </div>

                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700">Description (optionnel)</label>
                    <textarea
                        id="description"
                        name="description"
                        rows="3"
                        class="mt-1 block w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                    >{{ old('description') }}</textarea>
                </div>
            </div>

            <div class="flex items-center justify-end">
                <x-primary-button type="submit">
                    Créer
                </x-primary-button>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-200 flex items-start justify-between gap-4">
            <div>
                <h3 class="font-semibold text-lg text-gray-800">Liste des catégories</h3>
                <p class="mt-1 text-sm text-gray-600">{{ ($categories ?? collect())->count() }} catégorie(s)</p>
            </div>
        </div>

        @php
            $categoriesList = $categories ?? collect();
        @endphp

        @if($categoriesList->isEmpty())
            <div class="px-5 py-8 text-sm text-gray-600">
                Aucune catégorie trouvée.
            </div>
        @else
            <!-- Mobile: cards -->
            <div class="md:hidden divide-y divide-gray-200">
                @foreach($categoriesList as $cat)
                    <div class="p-4">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                        ID #{{ $cat->id_categorie ?? '-' }}
                                    </span>
                                </div>
                                <div class="mt-2 font-semibold text-gray-900 break-words">
                                    {{ $cat->nom_categorie ?? '-' }}
                                </div>
                                @if(!empty($cat->description))
                                    <div class="mt-1 text-sm text-gray-600 break-words">
                                        {{ $cat->description }}
                                    </div>
                                @endif
                            </div>

                            @if(!empty($cat->id_categorie))
                                <div class="shrink-0 flex items-center gap-2">
                                    <a
                                        href="{{ route('admin.categories.edit', ['id' => $cat->id_categorie]) }}"
                                        class="inline-flex items-center justify-center w-10 h-10 rounded-md text-blue-600 hover:bg-blue-50 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                                        title="Modifier"
                                        aria-label="Modifier"
                                    >
                                        <span class="sr-only">Modifier</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="w-5 h-5">
                                            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293z"/>
                                            <path d="M14.752 4.396 11.604 1.248l-7.59 7.59a.5.5 0 0 0-.128.196l-.944 3.33a.25.25 0 0 0 .31.31l3.33-.944a.5.5 0 0 0 .196-.128l7.59-7.59z"/>
                                            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                                        </svg>
                                    </a>

                                    <form
                                        action="{{ route('admin.categories.destroy', ['id' => $cat->id_categorie]) }}"
                                        method="POST"
                                        onsubmit="return confirm('Supprimer cette catégorie ?');"
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            type="submit"
                                            class="inline-flex items-center justify-center w-10 h-10 rounded-md text-red-600 hover:bg-red-50 hover:text-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
                                            title="Supprimer"
                                            aria-label="Supprimer"
                                        >
                                            <span class="sr-only">Supprimer</span>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="w-5 h-5">
                                                <path d="M5.5 5.5A.5.5 0 0 1 6 6v8a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0A.5.5 0 0 1 8 6v8a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v8a.5.5 0 0 0 1 0V6z"/>
                                                <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1 0-2H5.5a1 1 0 0 1 1-1h3a1 1 0 0 1 1 1H14a1 1 0 0 1 .5 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3h11-11z"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Desktop: table -->
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($categoriesList as $cat)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm text-gray-700 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                        #{{ $cat->id_categorie ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900 font-medium">
                                    {{ $cat->nom_categorie ?? '-' }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-700">
                                    <span class="block max-w-xl break-words">
                                        {{ $cat->description ?? '' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-right whitespace-nowrap">
                                    @if(!empty($cat->id_categorie))
                                        <div class="flex items-center justify-end gap-2">
                                            <a
                                                href="{{ route('admin.categories.edit', ['id' => $cat->id_categorie]) }}"
                                                class="inline-flex items-center justify-center w-9 h-9 rounded-md text-blue-600 hover:bg-blue-50 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                                                title="Modifier"
                                                aria-label="Modifier"
                                            >
                                                <span class="sr-only">Modifier</span>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="w-5 h-5">
                                                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293z"/>
                                                    <path d="M14.752 4.396 11.604 1.248l-7.59 7.59a.5.5 0 0 0-.128.196l-.944 3.33a.25.25 0 0 0 .31.31l3.33-.944a.5.5 0 0 0 .196-.128l7.59-7.59z"/>
                                                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                                                </svg>
                                            </a>

                                            <form
                                                action="{{ route('admin.categories.destroy', ['id' => $cat->id_categorie]) }}"
                                                method="POST"
                                                onsubmit="return confirm('Supprimer cette catégorie ?');"
                                            >
                                                @csrf
                                                @method('DELETE')
                                                <button
                                                    type="submit"
                                                    class="inline-flex items-center justify-center w-9 h-9 rounded-md text-red-600 hover:bg-red-50 hover:text-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
                                                    title="Supprimer"
                                                    aria-label="Supprimer"
                                                >
                                                    <span class="sr-only">Supprimer</span>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="w-5 h-5">
                                                        <path d="M5.5 5.5A.5.5 0 0 1 6 6v8a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0A.5.5 0 0 1 8 6v8a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v8a.5.5 0 0 0 1 0V6z"/>
                                                        <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1 0-2H5.5a1 1 0 0 1 1-1h3a1 1 0 0 1 1 1H14a1 1 0 0 1 .5 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3h11-11z"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
