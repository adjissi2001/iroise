@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="{{ asset('css/beneficiaires.css') }}">
    <style>
        .modal {
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 30px;
            border: 1px solid #888;
            border-radius: 8px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: #000;
        }

        .form-group input {
            box-sizing: border-box;
        }
    </style>
@endpush

@section('content')
    <div class="mb-6">
        @if(session('must_update_password'))
            <div class="mb-4 p-4 bg-yellow-100 border border-yellow-300 rounded">
                {{ session('must_update_password') }} <a href="{{ route('profile.edit') }}" class="text-blue-600 underline">Cliquez ici</a>.
            </div>
        @endif

        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-300 text-green-800 rounded">
                {{ session('success') }}
            </div>
        @endif

        <h2 class="font-semibold text-xl text-gray-800">Gestion des membres</h2>
        <div class="mt-4">
            <a href="{{ route('beneficiaire.create') }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-yellow-300 rounded-lg shadow hover:bg-green-700 transition">
                <i class="fa-solid fa-plus mr-2"></i> Ajouter un bénéficiaire
            </a>

            <a href="{{ route('beneficiaire.index', ['segment' => 'LB']) }}" class="ml-2 inline-flex items-center px-4 py-2 bg-gray-800 text-white rounded-lg shadow hover:bg-gray-900 transition">
                <i class="fa-solid fa-list mr-2"></i> Voir le module LB/LAB
            </a>
        </div>
    </div>

    @if(!empty($errorMessage))
        <div style="background:#fee2e2;color:#991b1b;border:1px solid #fecaca;padding:12px 16px;border-radius:10px;margin-bottom:16px;text-align:left;">
            {{ $errorMessage }}
        </div>
    @endif

    <div class="space-y-10">
        <section>
            <h3 class="font-semibold text-lg text-gray-800">LB : Liste Bénéficiaires</h3>

            @if(($lbBeneficiaires ?? collect())->isNotEmpty())
                <div class="overflow-x-auto mt-3">
                    <table id="beneficiairesTable" class="min-w-full">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Prénom</th>
                                <th>Date de naissance</th>
                                <th>Téléphone</th>
                                <th>Actions</th>
                            </tr>
                            <tr>
                                <th><input type="text" placeholder="Nom" /></th>
                                <th><input type="text" placeholder="Prénom" /></th>
                                <th><input type="text" placeholder="Date" /></th>
                                <th><input type="text" placeholder="Téléphone" /></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($lbBeneficiaires as $b)
                                <tr>
                                    <td data-label="Nom">{{ $b->nom }}</td>
                                    <td data-label="Prénom">{{ $b->prenom }}</td>
                                    <td data-label="Date de naissance">{{ $b->date_naissance }}</td>
                                    <td data-label="Téléphone">{{ $b->num_tel }}</td>
                                    <td data-label="Actions" style="text-align: center;">
                                        <button type="button" class="action-btn btn-edit" title="Modifier"
                                            onclick="openEditModal({{ json_encode($b) }})">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>

                                        <form action="{{ route('beneficiaire.destroy', $b->id_beneficiaire) }}"
                                            method="POST"
                                            onsubmit="return confirm('Confirmer la suppression ?');"
                                            style="display:inline;">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="action-btn btn-delete">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="mt-3">Aucun bénéficiaire trouvé.</p>
            @endif
        </section>

        <section>
            <h3 class="font-semibold text-lg text-gray-800">LAB : Liste Anciens Bénéficiaires</h3>

            @if(($labBeneficiaires ?? collect())->isNotEmpty())
                <div class="overflow-x-auto mt-3">
                    <table id="anciensBeneficiairesTable" class="min-w-full">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Prénom</th>
                                <th>Date de naissance</th>
                                <th>Téléphone</th>
                                <th>Actions</th>
                            </tr>
                            <tr>
                                <th><input type="text" placeholder="Nom" /></th>
                                <th><input type="text" placeholder="Prénom" /></th>
                                <th><input type="text" placeholder="Date" /></th>
                                <th><input type="text" placeholder="Téléphone" /></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($labBeneficiaires as $b)
                                <tr>
                                    <td data-label="Nom">{{ $b->nom }}</td>
                                    <td data-label="Prénom">{{ $b->prenom }}</td>
                                    <td data-label="Date de naissance">{{ $b->date_naissance }}</td>
                                    <td data-label="Téléphone">{{ $b->num_tel }}</td>
                                    <td data-label="Actions" style="text-align: center;">
                                        <button type="button" class="action-btn btn-edit" title="Modifier"
                                            onclick="openEditModal({{ json_encode($b) }})">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>

                                        <form action="{{ route('beneficiaire.destroy', $b->id_beneficiaire) }}"
                                            method="POST"
                                            onsubmit="return confirm('Confirmer la suppression ?');"
                                            style="display:inline;">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="action-btn btn-delete">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="mt-3">Aucun ancien bénéficiaire trouvé.</p>
            @endif
        </section>

        <section>
            <h3 class="font-semibold text-lg text-gray-800">LR : Liste Référents</h3>

            @if(($lrReferents ?? collect())->isNotEmpty())
                <div class="overflow-x-auto mt-3">
                    <table id="referentsTable" class="min-w-full">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Email</th>
                                <th>Actif</th>
                                <th>Actions</th>
                            </tr>
                            <tr>
                                <th><input type="text" placeholder="Nom" /></th>
                                <th><input type="text" placeholder="Email" /></th>
                                <th><input type="text" placeholder="Actif" /></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($lrReferents as $u)
                                <tr>
                                    <td data-label="Nom">{{ $u->display_name }}</td>
                                    <td data-label="Email">{{ $u->email }}</td>
                                    <td data-label="Actif">{{ (int) ($u->actif ?? 1) === 1 ? 'Oui' : 'Non' }}</td>
                                    <td data-label="Actions" style="text-align: center;">
                                        <a href="{{ route('user.show', $u->id) }}" class="action-btn btn-view" title="Voir">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if(auth()->user()->is_admin)
                                            <a href="{{ route('user.edit', $u->id) }}" class="action-btn btn-edit" title="Modifier">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="mt-3">Aucun référent trouvé.</p>
            @endif
        </section>

        <section>
            <h3 class="font-semibold text-lg text-gray-800">LAR : Liste Anciens Référents</h3>

            @if(($larReferents ?? collect())->isNotEmpty())
                <div class="overflow-x-auto mt-3">
                    <table id="anciensReferentsTable" class="min-w-full">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Email</th>
                                <th>Actif</th>
                                <th>Actions</th>
                            </tr>
                            <tr>
                                <th><input type="text" placeholder="Nom" /></th>
                                <th><input type="text" placeholder="Email" /></th>
                                <th><input type="text" placeholder="Actif" /></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($larReferents as $u)
                                <tr>
                                    <td data-label="Nom">{{ $u->display_name }}</td>
                                    <td data-label="Email">{{ $u->email }}</td>
                                    <td data-label="Actif">{{ (int) ($u->actif ?? 0) === 1 ? 'Oui' : 'Non' }}</td>
                                    <td data-label="Actions" style="text-align: center;">
                                        <a href="{{ route('user.show', $u->id) }}" class="action-btn btn-view" title="Voir">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if(auth()->user()->is_admin)
                                            <a href="{{ route('user.edit', $u->id) }}" class="action-btn btn-edit" title="Modifier">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="mt-3">Aucun ancien référent trouvé.</p>
            @endif
        </section>
    </div>

    <!-- Modal d'édition -->
    <div id="editModal" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close" onclick="closeEditModal()">&times;</span>
            <h2>Modifier le bénéficiaire</h2>
            <form id="editForm" method="POST" style="margin-top: 20px;">
                @csrf
                @method('PUT')
                
                <div class="form-group" style="margin-bottom: 15px;">
                    <label for="nom" style="display: block; margin-bottom: 5px; font-weight: bold;">Nom</label>
                    <input type="text" id="nom" name="nom" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 5px;">
                </div>

                <div class="form-group" style="margin-bottom: 15px;">
                    <label for="prenom" style="display: block; margin-bottom: 5px; font-weight: bold;">Prénom</label>
                    <input type="text" id="prenom" name="prenom" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 5px;">
                </div>

                <div class="form-group" style="margin-bottom: 15px;">
                    <label for="date_naissance" style="display: block; margin-bottom: 5px; font-weight: bold;">Date de naissance</label>
                    <input type="date" id="date_naissance" name="date_naissance" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 5px;">
                </div>

                <div class="form-group" style="margin-bottom: 15px;">
                    <label for="num_tel" style="display: block; margin-bottom: 5px; font-weight: bold;">Téléphone</label>
                    <input type="tel" id="num_tel" name="num_tel" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 5px;">
                </div>

                <div style="display: flex; gap: 10px; margin-top: 25px;">
                    <button type="submit" class="action-btn btn-edit" style="flex: 1; padding: 10px; background-color: #4CAF50; color: white; border: none; border-radius: 5px; cursor: pointer;">
                        Enregistrer
                    </button>
                    <button type="button" class="action-btn btn-cancel" onclick="closeEditModal()" style="flex: 1; padding: 10px; background-color: #f44336; color: white; border: none; border-radius: 5px; cursor: pointer;">
                        Annuler
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
        <script src="{{ asset('js/beneficiaires.js') }}"></script>
        <script>
            function openEditModal(beneficiaire) {
                document.getElementById('nom').value = beneficiaire.nom;
                document.getElementById('prenom').value = beneficiaire.prenom;
                document.getElementById('date_naissance').value = beneficiaire.date_naissance;
                document.getElementById('num_tel').value = beneficiaire.num_tel;
                
                // Mettre à jour l'action du formulaire
                document.getElementById('editForm').action = "{{ route('beneficiaire.update', ':id') }}".replace(':id', beneficiaire.id_beneficiaire);
                
                document.getElementById('editModal').style.display = 'block';
            }

            function closeEditModal() {
                document.getElementById('editModal').style.display = 'none';
            }

            // Fermer le modal en cliquant en dehors
            window.onclick = function(event) {
                var modal = document.getElementById('editModal');
                if (event.target == modal) {
                    modal.style.display = 'none';
                }
            }

            // Init DataTables for extra tables on this page (keep beneficiaires.js for #beneficiairesTable)
            $(document).ready(function () {
                function initTable(id) {
                    var $table = $('#' + id);
                    if ($table.length === 0) return;

                    var dt = $table.DataTable({
                        orderCellsTop: true,
                        fixedHeader: true,
                        pageLength: 10,
                        lengthMenu: [5, 10, 25, 50],
                        language: {
                            url: "//cdn.datatables.net/plug-ins/1.13.8/i18n/fr-FR.json"
                        }
                    });

                    // Recherche par colonne (2e ligne du header)
                    $('#' + id + ' thead tr:eq(1) th').each(function (i) {
                        $('input', this).on('keyup change clear', function () {
                            if (dt.column(i).search() !== this.value) {
                                dt.column(i).search(this.value).draw();
                            }
                        });
                    });
                }

                initTable('anciensBeneficiairesTable');
                initTable('referentsTable');
                initTable('anciensReferentsTable');
            });
        </script>
    @endpush
@endsection
