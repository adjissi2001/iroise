<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Statistiques') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Onglets -->
            <div class="mb-8" x-data="{ activeTab: 'general' }">
                <div class="flex space-x-8 border-b-2 border-gray-300 dark:border-gray-600">
                    <button 
                        @click="activeTab = 'general'" 
                        :class="{ 'border-b-2 border-blue-600 text-blue-600': activeTab === 'general', 'text-gray-500 dark:text-gray-400': activeTab !== 'general' }"
                        class="px-1 py-3 font-medium text-sm transition duration-300">
                        Général
                    </button>
                    <button 
                        @click="activeTab = 'mois'" 
                        :class="{ 'border-b-2 border-blue-600 text-blue-600': activeTab === 'mois', 'text-gray-500 dark:text-gray-400': activeTab !== 'mois' }"
                        class="px-1 py-3 font-medium text-sm transition duration-300">
                        Par mois
                    </button>
                    <button 
                        @click="activeTab = 'annee'" 
                        :class="{ 'border-b-2 border-blue-600 text-blue-600': activeTab === 'annee', 'text-gray-500 dark:text-gray-400': activeTab !== 'annee' }"
                        class="px-1 py-3 font-medium text-sm transition duration-300">
                        Par année
                    </button>
                    <button 
                        @click="activeTab = 'nonprise'" 
                        :class="{ 'border-b-2 border-blue-600 text-blue-600': activeTab === 'nonprise', 'text-gray-500 dark:text-gray-400': activeTab !== 'nonprise' }"
                        class="px-1 py-3 font-medium text-sm transition duration-300">
                        Missions non prises
                    </button>
                </div>

                <!-- Tab: Général -->
                <div x-show="activeTab === 'general'" class="mt-6">
                    {{-- Cartes statistiques principales --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

                        {{-- Nombre de missions --}}
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-blue-500 rounded-full p-3">
                                    <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                </div>
                                <div class="ml-5">
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Missions totales') }}</p>
                                    <p class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $totalMissions }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Heures de bénévolat --}}
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-green-500 rounded-full p-3">
                                    <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div class="ml-5">
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Heures de bénévolat') }}</p>
                                    <p class="text-3xl font-bold text-gray-900 dark:text-gray-100">
                                        {{ $totalHeures }}<span class="text-lg">h</span>{{ str_pad($totalMinutes, 2, '0', STR_PAD_LEFT) }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- Kilométrage --}}
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-orange-500 rounded-full p-3">
                                    <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                    </svg>
                                </div>
                                <div class="ml-5">
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Kilométrage total') }}</p>
                                    <p class="text-3xl font-bold text-gray-900 dark:text-gray-100">
                                        {{ number_format($totalKilometrage, 0, ',', ' ') }} <span class="text-lg">km</span>
                                    </p>
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- Détail par état des missions --}}
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

                        {{-- Missions validées --}}
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-5">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Missions validées') }}</p>
                                    <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $missionsValidees }}</p>
                                </div>
                                <div class="text-sm font-medium text-gray-500">
                                    {{ $statistique->pourcentage($missionsValidees) }}%
                                </div>
                            </div>
                        </div>

                        {{-- Missions en cours --}}
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-5">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Missions en cours') }}</p>
                                    <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $missionsEnCours }}</p>
                                </div>
                                <div class="text-sm font-medium text-gray-500">
                                    {{ $statistique->pourcentage($missionsEnCours) }}%
                                </div>
                            </div>
                        </div>

                        {{-- Missions annulées --}}
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-5">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Missions annulées') }}</p>
                                    <p class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $missionsAnnulees }}</p>
                                </div>
                                <div class="text-sm font-medium text-gray-500">
                                    {{ $statistique->pourcentage($missionsAnnulees) }}%
                                </div>
                            </div>
                        </div>

                        {{-- Missions non prises --}}
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-5">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Missions non prises') }}</p>
                                    <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $missionsNonPrises }}</p>
                                </div>
                                <div class="text-sm font-medium text-gray-500">
                                    {{ $statistique->pourcentage($missionsNonPrises) }}%
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Tab: Par mois -->
                <div x-show="activeTab === 'mois'" class="mt-8">
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                            <h3 class="text-base font-semibold text-gray-900 dark:text-white">
                                Statistiques de {{ $anneeActuelle }}
                            </h3>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="bg-gray-50 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 border-r border-gray-200 dark:border-gray-700">Mois</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 border-r border-gray-200 dark:border-gray-700">Missions</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 border-r border-gray-200 dark:border-gray-700">Kilométrage</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300">Heures bénévolat</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @forelse($statsMois as $stat)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition ease-in-out duration-150">
                                            <td class="px-6 py-3 text-sm font-medium text-gray-900 dark:text-gray-100 border-r border-gray-200 dark:border-gray-700">{{ $stat['mois'] }}</td>
                                            <td class="px-6 py-3 text-sm text-gray-700 dark:text-gray-300 border-r border-gray-200 dark:border-gray-700">
                                                <span class="inline-block px-2 py-1 rounded bg-blue-50 dark:bg-blue-900 text-blue-700 dark:text-blue-200 font-semibold text-xs">
                                                    {{ $stat['total'] }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-3 text-sm text-gray-700 dark:text-gray-300 border-r border-gray-200 dark:border-gray-700">
                                                <span class="inline-block px-2 py-1 rounded bg-orange-50 dark:bg-orange-900 text-orange-700 dark:text-orange-200 font-semibold text-xs">
                                                    {{ $stat['km'] }} km
                                                </span>
                                            </td>
                                            <td class="px-6 py-3 text-sm text-gray-700 dark:text-gray-300">
                                                <span class="inline-block px-2 py-1 rounded bg-green-50 dark:bg-green-900 text-green-700 dark:text-green-200 font-semibold text-xs">
                                                    {{ $stat['heures'] }}h{{ str_pad($stat['minutes'], 2, '0', STR_PAD_LEFT) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400 text-sm">
                                                Aucune donnée disponible
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Tab: Par année -->
                <div x-show="activeTab === 'annee'" class="mt-8">
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                            <h3 class="text-base font-semibold text-gray-900 dark:text-white">
                                Statistiques annuelles
                            </h3>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="bg-gray-50 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 border-r border-gray-200 dark:border-gray-700">Année</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 border-r border-gray-200 dark:border-gray-700">Missions</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 border-r border-gray-200 dark:border-gray-700">Kilométrage</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300">Heures bénévolat</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @forelse($statsAnnee as $stat)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition ease-in-out duration-150">
                                            <td class="px-6 py-3 text-sm font-medium text-gray-900 dark:text-gray-100 border-r border-gray-200 dark:border-gray-700">{{ $stat['annee'] }}</td>
                                            <td class="px-6 py-3 text-sm text-gray-700 dark:text-gray-300 border-r border-gray-200 dark:border-gray-700">
                                                <span class="inline-block px-2 py-1 rounded bg-blue-50 dark:bg-blue-900 text-blue-700 dark:text-blue-200 font-semibold text-xs">
                                                    {{ $stat['total'] }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-3 text-sm text-gray-700 dark:text-gray-300 border-r border-gray-200 dark:border-gray-700">
                                                <span class="inline-block px-2 py-1 rounded bg-orange-50 dark:bg-orange-900 text-orange-700 dark:text-orange-200 font-semibold text-xs">
                                                    {{ $stat['km'] }} km
                                                </span>
                                            </td>
                                            <td class="px-6 py-3 text-sm text-gray-700 dark:text-gray-300">
                                                <span class="inline-block px-2 py-1 rounded bg-green-50 dark:bg-green-900 text-green-700 dark:text-green-200 font-semibold text-xs">
                                                    {{ $stat['heures'] }}h{{ str_pad($stat['minutes'], 2, '0', STR_PAD_LEFT) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400 text-sm">
                                                Aucune donnée disponible
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Tab: Missions non prises -->
                <div x-show="activeTab === 'nonprise'" class="mt-8">
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                            <h3 class="text-base font-semibold text-gray-900 dark:text-white">
                                Missions non prises ({{ count($missionsNonPrisesData) }})
                            </h3>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="bg-gray-50 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 border-r border-gray-200 dark:border-gray-700">Lieu</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 border-r border-gray-200 dark:border-gray-700">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 border-r border-gray-200 dark:border-gray-700">Kilométrage</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300">État</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @forelse($missionsNonPrisesData as $mission)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition ease-in-out duration-150">
                                            <td class="px-6 py-3 text-sm font-medium text-gray-900 dark:text-gray-100 border-r border-gray-200 dark:border-gray-700">
                                                {{ $mission['lieu'] }}
                                            </td>
                                            <td class="px-6 py-3 text-sm text-gray-700 dark:text-gray-300 border-r border-gray-200 dark:border-gray-700">
                                                {{ \Carbon\Carbon::parse($mission['date'])->format('d/m/Y') }}
                                            </td>
                                            <td class="px-6 py-3 text-sm text-gray-700 dark:text-gray-300 border-r border-gray-200 dark:border-gray-700">
                                                <span class="inline-block px-2 py-1 rounded bg-orange-50 dark:bg-orange-900 text-orange-700 dark:text-orange-200 font-semibold text-xs">
                                                    {{ $mission['km'] }} km
                                                </span>
                                            </td>
                                            <td class="px-6 py-3 text-sm text-gray-700 dark:text-gray-300">
                                                <span class="inline-block px-2 py-1 rounded bg-yellow-50 dark:bg-yellow-900 text-yellow-700 dark:text-yellow-200 font-semibold text-xs uppercase">
                                                    {{ $mission['etat'] }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400 text-sm">
                                                ✅ Toutes les missions ont été assignées !
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
