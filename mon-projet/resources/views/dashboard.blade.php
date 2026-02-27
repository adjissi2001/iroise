@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if(session('success'))
                        <div class="mb-4 p-4 bg-green-100 border border-green-300 text-green-800 rounded">
                            {{ session('success') }}
                        </div>
                    @endif
                    <h2 class="font-semibold text-xl mb-4">{{ __('Dashboard') }}</h2>
                    <p>{{ __("Vous êtes connecté !") }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection
