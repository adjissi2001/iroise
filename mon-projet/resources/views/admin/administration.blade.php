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
        <h2 class="font-semibold text-xl text-gray-800">Liste des Bénéficiaires</h2>
    </div>

    @if(!empty($errorMessage))
        <div style="background:#fee2e2;color:#991b1b;border:1px solid #fecaca;padding:12px 16px;border-radius:10px;margin-bottom:16px;text-align:left;">
            {{ $errorMessage }}
        </div>
    @endif

    @if(!empty($beneficiaires))
        <div class="overflow-x-auto">
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
                    @foreach ($beneficiaires as $b)
                        <tr>
                            <td data-label="Nom">{{ $b->nom }}</td>
                            <td data-label="Prénom">{{ $b->prenom }}</td>
                            <td data-label="Date de naissance">{{ $b->date_naissance }}</td>
                            <td data-label="Téléphone">{{ $b->num_tel }}</td>
                            <td data-label="Actions" style="text-align: center;">
                                <!-- Modifier -->
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
        <p>Aucun bénéficiaire trouvé.</p>
    @endif

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

            $(document).ready(function() {
                var table = $('#beneficiairesTable').DataTable({
                    orderCellsTop: true,
                    pageLength: 10,
                    language: {
                        url: "//cdn.datatables.net/plug-ins/1.13.8/i18n/fr-FR.json"
                    }
                });

                // Filtrage par colonne
                $('#beneficiairesTable thead tr:eq(1) th').each(function(i) {
                    var select = $('<input type="text" placeholder="Chercher..." />')
                        .appendTo($(this).empty())
                        .on('keyup change', function() {
                            table.column(i).search(this.value).draw();
                        });
                });
            });
        </script>
    @endpush
@endsection
