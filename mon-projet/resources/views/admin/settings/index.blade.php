@extends('layouts.app')

@section('content')
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <h2 class="font-semibold text-xl text-gray-800">Paramètres</h2>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="bg-white rounded-lg border border-gray-200 p-5">
            <h3 class="font-semibold text-lg text-gray-800">Catégories</h3>
            <p class="mt-1 text-sm text-gray-600">Gérer les catégories de mission.</p>

            <div class="mt-4">
                <a href="{{ route('admin.categories.index') }}">
                    <x-primary-button>
                        Ouvrir
                    </x-primary-button>
                </a>
            </div>
        </div>
    </div>
@endsection
