@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-7xl mx-auto">

        {{-- ── En-tête ────────────────────────────────────────────────── --}}
        <div class="mb-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Agenda des missions</h1>
                @if($isManager)
                    <p class="text-gray-500 text-sm mt-1">Cliquez sur une date pour ajouter une mission, sur une mission pour la gérer</p>
                @else
                    <p class="text-gray-500 text-sm mt-1">Cliquez sur une mission disponible pour la prendre en charge</p>
                @endif
            </div>
            <div class="flex items-center gap-3">
                {{-- Légende --}}
                <div class="hidden md:flex items-center gap-3 text-xs">
                    <span class="flex items-center gap-1"><span style="width:12px;height:12px;border-radius:9999px;background:#f97316;display:inline-block;"></span> Disponible</span>
                    <span class="flex items-center gap-1"><span style="width:12px;height:12px;border-radius:9999px;background:#3b82f6;display:inline-block;"></span> Prise</span>
                    <span class="flex items-center gap-1"><span style="width:12px;height:12px;border-radius:9999px;background:#10b981;display:inline-block;"></span> Validée</span>
                    <span class="flex items-center gap-1"><span style="width:12px;height:12px;border-radius:9999px;background:#ef4444;display:inline-block;"></span> Annulée</span>
                </div>
            </div>
        </div>

        {{-- ── Messages flash ─────────────────────────────────────────── --}}
        @if (session('success'))
            <div class="mb-4 bg-green-50 border border-green-200 rounded-lg p-3 text-green-700 text-sm">
                {{ session('success') }}
            </div>
        @endif

        {{-- ── Calendrier FullCalendar ────────────────────────────────── --}}
        <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6">
            <div id="calendar"></div>
        </div>

    </div>
</div>

{{-- ── Toast container ──────────────────────────────────────────────── --}}
<div id="toastContainer" class="fixed top-4 right-4 z-[9999] space-y-2"></div>

{{-- ══════════════════════════════════════════════════════════════════════ --}}
{{-- MODAL détail mission                                                   --}}
{{-- ══════════════════════════════════════════════════════════════════════ --}}
<div id="missionModal" class="fixed inset-0 z-50 hidden">
    <div id="modalOverlay" class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div id="modalBox" class="bg-white rounded-2xl shadow-2xl relative overflow-hidden" style="width:380px; max-width:90vw; min-height:340px; border:3px solid #10b981;">
            <div id="modalBanner" class="h-2" style="display:none;"></div>
            <div class="p-6" style="display:flex; flex-direction:column; justify-content:center; min-height:332px;">
                {{-- Bouton fermer --}}
                <button id="modalClose" style="position:absolute; top:0.5rem; left:0.75rem; font-size:1.5rem; font-weight:bold; color:#6b7280; background:none; border:none; cursor:pointer; z-index:10; line-height:1;" onmouseover="this.style.color='#111827'" onmouseout="this.style.color='#6b7280'">&times;</button>

                {{-- Titre --}}
                <h2 id="modalTitle" class="text-xl font-bold text-gray-800 mb-4"></h2>

                {{-- Infos --}}
                <div class="space-y-3 text-sm text-gray-600">
                    <div id="modalDate" class="flex items-center gap-2">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span></span>
                    </div>
                    <div id="modalTime" class="flex items-center gap-2">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span></span>
                    </div>
                    <div id="modalDescription" class="flex items-start gap-2">
                        <svg class="h-4 w-4 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                        <span></span>
                    </div>
                    <div id="modalBenevole" class="flex items-center gap-2" style="display:none;">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        <span></span>
                    </div>
                    <div id="modalEtat" class="flex items-center gap-2">
                        <span class="font-medium">État :</span>
                        <span id="modalEtatBadge" class="px-2 py-0.5 rounded-full text-xs font-semibold text-white"></span>
                    </div>
                </div>

                {{-- Boutons d'action --}}
                <div id="modalActions" style="margin-top:1.5rem; display:flex; align-items:center; gap:0.5rem; flex-wrap:wrap;">
                    {{-- Boutons manager (admin/référent) --}}
                    @if($isManager)
                    <button id="btnModifier" style="display:inline-flex; align-items:center; justify-content:center; width:36px; height:36px; background:#eff6ff; border:1px solid #bfdbfe; border-radius:0.5rem; cursor:pointer; color:#2563eb;" title="Modifier" onmouseover="this.style.background='#dbeafe'" onmouseout="this.style.background='#eff6ff'">
                        <svg style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </button>
                    <button id="btnAnnuler" style="display:inline-flex; align-items:center; justify-content:center; width:36px; height:36px; background:#fef2f2; border:1px solid #fecaca; border-radius:0.5rem; cursor:pointer; color:#dc2626;" title="Annuler la mission" onmouseover="this.style.background='#fee2e2'" onmouseout="this.style.background='#fef2f2'">
                        <svg style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                    </button>
                    @endif

                    {{-- Boutons bénévole --}}
                    <button id="btnPrendre" style="display:none; align-items:center; gap:0.375rem; padding:0.5rem 1rem; background:#059669; color:#fff; border-radius:0.5rem; border:none; cursor:pointer; font-weight:600; font-size:0.875rem;">
                        ✋ Prendre
                    </button>
                    <button id="btnRetirer" style="display:none; align-items:center; gap:0.375rem; padding:0.5rem 1rem; background:#fff7ed; color:#ea580c; border:1px solid #fed7aa; border-radius:0.5rem; cursor:pointer; font-weight:600; font-size:0.875rem;">
                        Retirer
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@if($isManager)
{{-- ══════════════════════════════════════════════════════════════════════ --}}
{{-- MODAL création mission                                                 --}}
{{-- ══════════════════════════════════════════════════════════════════════ --}}
<div id="createModal" class="fixed inset-0 z-50 hidden">
    <div id="createOverlay" class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg relative overflow-hidden">
            <div class="h-2 bg-emerald-500"></div>
            <div class="p-6">
                <button onclick="closeCreateModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 text-xl">&times;</button>
                <h2 class="text-xl font-bold text-gray-800 mb-5">Ajouter une mission</h2>

                <form id="createForm" class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Catégorie</label>
                            <select id="create_categorie" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                                <option value="">-- sélectionner --</option>
                                @foreach(($categories ?? collect()) as $c)
                                    <option value="{{ $c->id_categorie }}">{{ $c->nom_categorie }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Lieu</label>
                            <input type="text" id="create_lieu" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" placeholder="Ex: Brest centre">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Date de départ <span class="text-red-500">*</span></label>
                            <input type="date" id="create_date_depart" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Heure départ</label>
                            <input type="time" id="create_heure_depart" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Heure arrivée</label>
                            <input type="time" id="create_heure_arrivee" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Assigner à un bénévole</label>
                        <select id="create_benevole" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                            <option value="">-- non assignée --</option>
                            @foreach($benevoles as $b)
                                <option value="{{ $b->id }}">{{ optional($b->profil)->prenom }} {{ optional($b->profil)->nom }} ({{ $b->email }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Remarques</label>
                        <textarea id="create_description" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" placeholder="Description de la mission..."></textarea>
                    </div>

                    <div style="display:flex; justify-content:flex-end; gap:0.75rem; padding-top:0.5rem;">
                        <button type="button" onclick="closeCreateModal()" style="padding:0.5rem 1rem; font-size:0.875rem; font-weight:500; color:#374151; background:#f3f4f6; border-radius:0.5rem; border:none; cursor:pointer;">Annuler</button>
                        <button type="submit" id="createSubmitBtn" style="padding:0.5rem 1.25rem; font-size:0.875rem; font-weight:600; color:#fff; background:#059669; border-radius:0.5rem; border:none; cursor:pointer; box-shadow:0 1px 3px rgba(0,0,0,0.15);">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════════════ --}}
{{-- MODAL édition mission                                                  --}}
{{-- ══════════════════════════════════════════════════════════════════════ --}}
<div id="editModal" class="fixed inset-0 z-50 hidden">
    <div id="editOverlay" class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg relative overflow-hidden">
            <div class="h-2 bg-blue-500"></div>
            <div class="p-6">
                <button onclick="closeEditModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 text-xl">&times;</button>
                <h2 class="text-xl font-bold text-gray-800 mb-5">Modifier la mission</h2>

                <form id="editForm" class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Catégorie</label>
                            <select id="edit_categorie" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">-- sélectionner --</option>
                                @foreach(($categories ?? collect()) as $c)
                                    <option value="{{ $c->id_categorie }}">{{ $c->nom_categorie }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Lieu</label>
                            <input type="text" id="edit_lieu" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Date de départ</label>
                            <input type="date" id="edit_date_depart" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Heure départ</label>
                            <input type="time" id="edit_heure_depart" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Heure arrivée</label>
                            <input type="time" id="edit_heure_arrivee" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Assigner à un bénévole</label>
                        <select id="edit_benevole" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">-- non assignée --</option>
                            @foreach($benevoles as $b)
                                <option value="{{ $b->id }}">{{ optional($b->profil)->prenom }} {{ optional($b->profil)->nom }} ({{ $b->email }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Remarques</label>
                        <textarea id="edit_description" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">État</label>
                        <select id="edit_etat" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="non_prise">Disponible</option>
                            <option value="prise">Prise</option>
                            <option value="validee">Validée</option>
                            <option value="annulee">Annulée</option>
                        </select>
                    </div>

                    <div style="display:flex; justify-content:flex-end; gap:0.75rem; padding-top:0.5rem;">
                        <button type="button" onclick="closeEditModal()" style="padding:0.5rem 1rem; font-size:0.875rem; font-weight:500; color:#374151; background:#f3f4f6; border-radius:0.5rem; border:none; cursor:pointer;">Annuler</button>
                        <button type="submit" id="editSubmitBtn" style="padding:0.5rem 1.25rem; font-size:0.875rem; font-weight:600; color:#fff; background:#2563eb; border-radius:0.5rem; border:none; cursor:pointer; box-shadow:0 1px 3px rgba(0,0,0,0.15);">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

{{-- ══════════════════════════════════════════════════════════════════════ --}}
{{-- CSS & JS                                                              --}}
{{-- ══════════════════════════════════════════════════════════════════════ --}}
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const IS_MANAGER = {{ $isManager ? 'true' : 'false' }};

    const modal        = document.getElementById('missionModal');
    const modalOverlay = document.getElementById('modalOverlay');
    const modalClose   = document.getElementById('modalClose');
    const btnPrendre   = document.getElementById('btnPrendre');
    const btnRetirer   = document.getElementById('btnRetirer');
    const btnModifier  = IS_MANAGER ? document.getElementById('btnModifier') : null;
    const btnAnnuler   = IS_MANAGER ? document.getElementById('btnAnnuler') : null;

    let currentEvent = null;
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    // ── Helpers ──────────────────────────────────────────────────────────
    function showToast(message, type = 'success') {
        const container = document.getElementById('toastContainer');
        const colors = { success: 'bg-emerald-500', error: 'bg-red-500', info: 'bg-blue-500' };
        const toast = document.createElement('div');
        toast.className = `${colors[type] || colors.info} text-white px-5 py-3 rounded-lg shadow-lg text-sm font-medium transform translate-x-full transition-transform duration-300`;
        toast.textContent = message;
        container.appendChild(toast);
        requestAnimationFrame(() => toast.classList.remove('translate-x-full'));
        setTimeout(() => { toast.classList.add('translate-x-full'); setTimeout(() => toast.remove(), 300); }, 3000);
    }

    const etatLabels = { 'non_prise': 'Disponible', 'prise': 'Prise', 'validee': 'Validée', 'annulee': 'Annulée' };

    // ── Initialisation FullCalendar ────────────────────────────────────
    const calendarEl = document.getElementById('calendar');
    const calendarConfig = {
        locale: 'fr',
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
        },
        buttonText: {
            today: "Aujourd'hui",
            month: 'Mois',
            week:  'Semaine',
            day:   'Jour',
            list:  'Liste'
        },
        navLinks: true,
        editable: false,
        selectable: false,
        dayMaxEvents: 3,
        height: 'auto',

        events: {
            url: '{{ route("agenda.events") }}',
            method: 'GET',
            failure: function () {
                showToast('Erreur lors du chargement des missions.', 'error');
            }
        },

        eventClick: function (info) {
            info.jsEvent.preventDefault();
            const event = info.event;
            const props = event.extendedProps;

            currentEvent = { id: event.id, props: props, bg: event.backgroundColor };

            // Remplir la modal
            document.getElementById('modalTitle').textContent = event.title;
            document.getElementById('modalBox').style.borderColor = event.backgroundColor;

            const dateObj = event.start;
            document.querySelector('#modalDate span').textContent = dateObj
                ? dateObj.toLocaleDateString('fr-FR', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' })
                : 'Non définie';

            let timeText = '';
            if (props.heure_depart) timeText += props.heure_depart;
            if (props.heure_arrivee) timeText += ' → ' + props.heure_arrivee;
            document.querySelector('#modalTime span').textContent = timeText || 'Horaire non précisé';

            document.querySelector('#modalDescription span').textContent = props.description || 'Aucune description';

            // Afficher le bénévole assigné
            const benevoleDiv = document.getElementById('modalBenevole');
            if (props.benevole_name && props.benevole_name.trim()) {
                benevoleDiv.style.display = 'flex';
                benevoleDiv.querySelector('span').textContent = 'Bénévole : ' + props.benevole_name;
            } else {
                benevoleDiv.style.display = 'none';
            }

            const badge = document.getElementById('modalEtatBadge');
            badge.textContent = etatLabels[props.etat] || props.etat;
            badge.style.backgroundColor = event.backgroundColor;

            // Afficher/masquer les boutons selon le rôle et l'état
            const isAnnulee = props.etat === 'annulee';

            if (IS_MANAGER) {
                btnModifier.style.display = isAnnulee ? 'none' : 'inline-flex';
                btnAnnuler.style.display = isAnnulee ? 'none' : 'inline-flex';
            }

            // Tout le monde peut prendre/retirer une mission
            btnPrendre.style.display = props.can_take ? 'inline-flex' : 'none';
            btnRetirer.style.display = props.is_mine ? 'inline-flex' : 'none';

            modal.classList.remove('hidden');
        }
    };

    // ── Clic sur une date → ouvrir le formulaire d'ajout (managers seulement)
    if (IS_MANAGER) {
        calendarConfig.dateClick = function (info) {
            openCreateModal(info.dateStr);
        };
    }

    const calendar = new FullCalendar.Calendar(calendarEl, calendarConfig);
    calendar.render();

    // ── Fermer la modal détail ──────────────────────────────────────────
    function closeModal() { modal.classList.add('hidden'); currentEvent = null; }
    modalClose.addEventListener('click', closeModal);
    modalOverlay.addEventListener('click', closeModal);

    // ── Modifier → ouvrir le formulaire d'édition (managers seulement) ──
    if (IS_MANAGER && btnModifier) {
        btnModifier.addEventListener('click', function () {
            if (!currentEvent) return;
            const props = currentEvent.props;

            document.getElementById('edit_categorie').value = props.id_categorie || '';
            document.getElementById('edit_lieu').value = props.lieu || '';

            const calEvent = calendar.getEventById(currentEvent.id);
            if (calEvent && calEvent.start) {
                document.getElementById('edit_date_depart').value = calEvent.start.toISOString().split('T')[0];
            }

            document.getElementById('edit_heure_depart').value = props.heure_depart || '';
            document.getElementById('edit_heure_arrivee').value = props.heure_arrivee || '';
            document.getElementById('edit_description').value = props.description || '';
            document.getElementById('edit_etat').value = props.etat || 'non_prise';
            document.getElementById('edit_benevole').value = props.benevole_id || '';

            closeModal();
            document.getElementById('editModal').classList.remove('hidden');
        });
    }

    // ── Annuler la mission (managers seulement) ─────────────────────────
    if (IS_MANAGER && btnAnnuler) {
        btnAnnuler.addEventListener('click', async function () {
            if (!currentEvent || !confirm('Confirmer l\'annulation de cette mission ?')) return;
            try {
                const resp = await fetch(`/agenda/missions/${currentEvent.id}/annuler`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json', 'Content-Type': 'application/json' }
                });
                const data = await resp.json();
                if (resp.ok) {
                    showToast(data.message || 'Mission annulée.');
                    closeModal();
                    calendar.refetchEvents();
                } else {
                    showToast(data.error || 'Erreur', 'error');
                }
            } catch { showToast('Erreur réseau', 'error'); }
        });
    }

    // ── Prendre une mission (bénévoles) ─────────────────────────────────
    btnPrendre.addEventListener('click', async function () {
        if (!currentEvent) return;
        btnPrendre.disabled = true;
        btnPrendre.textContent = 'Chargement...';
        try {
            const resp = await fetch(`/agenda/missions/${currentEvent.id}/prendre`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json', 'Content-Type': 'application/json' }
            });
            const data = await resp.json();
            if (resp.ok) {
                showToast(data.success || 'Mission prise !');
                closeModal();
                calendar.refetchEvents();
            } else {
                showToast(data.error || 'Erreur', 'error');
            }
        } catch { showToast('Erreur réseau', 'error'); }
        btnPrendre.disabled = false;
        btnPrendre.textContent = '✋ Prendre';
    });

    // ── Retirer une mission (bénévoles — libérer) ───────────────────────
    btnRetirer.addEventListener('click', async function () {
        if (!currentEvent || !confirm('Voulez-vous vraiment libérer cette mission ?')) return;
        btnRetirer.disabled = true;
        try {
            const resp = await fetch(`/agenda/missions/${currentEvent.id}/retirer`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json', 'Content-Type': 'application/json' }
            });
            const data = await resp.json();
            if (resp.ok) {
                showToast(data.success || 'Mission libérée.');
                closeModal();
                calendar.refetchEvents();
            } else {
                showToast(data.error || 'Erreur', 'error');
            }
        } catch { showToast('Erreur réseau', 'error'); }
        btnRetirer.disabled = false;
    });

    // ── Formulaire d'édition : submit (managers) ────────────────────────
    if (IS_MANAGER) {
        window.closeEditModal = function () {
            document.getElementById('editModal').classList.add('hidden');
        };

        document.getElementById('editOverlay').addEventListener('click', closeEditModal);

        document.getElementById('editForm').addEventListener('submit', async function (e) {
            e.preventDefault();
            if (!currentEvent) return;

            const submitBtn = document.getElementById('editSubmitBtn');
            submitBtn.disabled = true;
            submitBtn.textContent = 'Enregistrement...';

            const payload = {
                categorie: document.getElementById('edit_categorie').value || null,
                lieu: document.getElementById('edit_lieu').value || null,
                date_depart: document.getElementById('edit_date_depart').value,
                heure_depart: document.getElementById('edit_heure_depart').value || null,
                heure_arrivee: document.getElementById('edit_heure_arrivee').value || null,
                description: document.getElementById('edit_description').value || null,
                etat: document.getElementById('edit_etat').value,
                benevole_id: document.getElementById('edit_benevole').value || null,
            };

            try {
                const resp = await fetch(`/agenda/missions/${currentEvent.id}`, {
                    method: 'PUT',
                    headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json', 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });
                const data = await resp.json();
                if (resp.ok) {
                    showToast(data.message || 'Mission mise à jour !');
                    closeEditModal();
                    calendar.refetchEvents();
                } else {
                    if (data.errors) {
                        showToast(Object.values(data.errors).flat().join('\n'), 'error');
                    } else {
                        showToast(data.message || 'Erreur', 'error');
                    }
                }
            } catch { showToast('Erreur réseau', 'error'); }

            submitBtn.disabled = false;
            submitBtn.textContent = 'Enregistrer';
        });

        // ── Modal Création (managers) ───────────────────────────────────
        window.openCreateModal = function (dateStr) {
            document.getElementById('create_categorie').value = '';
            document.getElementById('create_lieu').value = '';
            document.getElementById('create_date_depart').value = dateStr || '';
            document.getElementById('create_heure_depart').value = '';
            document.getElementById('create_heure_arrivee').value = '';
            document.getElementById('create_description').value = '';
            document.getElementById('create_benevole').value = '';
            document.getElementById('createModal').classList.remove('hidden');
        };

        window.closeCreateModal = function () {
            document.getElementById('createModal').classList.add('hidden');
        };

        document.getElementById('createOverlay').addEventListener('click', closeCreateModal);

        document.getElementById('createForm').addEventListener('submit', async function (e) {
            e.preventDefault();
            const submitBtn = document.getElementById('createSubmitBtn');
            submitBtn.disabled = true;
            submitBtn.textContent = 'Enregistrement...';

            const payload = {
                categorie: document.getElementById('create_categorie').value || null,
                lieu: document.getElementById('create_lieu').value || null,
                date_depart: document.getElementById('create_date_depart').value,
                heure_depart: document.getElementById('create_heure_depart').value || null,
                heure_arrivee: document.getElementById('create_heure_arrivee').value || null,
                description: document.getElementById('create_description').value || null,
                benevole_id: document.getElementById('create_benevole').value || null,
            };

            try {
                const resp = await fetch('/agenda/missions', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json', 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });
                const data = await resp.json();
                if (resp.ok) {
                    showToast(data.message || 'Mission ajoutée !');
                    closeCreateModal();
                    calendar.refetchEvents();
                } else {
                    if (data.errors) {
                        showToast(Object.values(data.errors).flat().join('\n'), 'error');
                    } else {
                        showToast(data.message || 'Erreur', 'error');
                    }
                }
            } catch { showToast('Erreur réseau', 'error'); }

            submitBtn.disabled = false;
            submitBtn.textContent = 'Enregistrer';
        });
    }

    // ── Escape pour fermer ──────────────────────────────────────────────
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeModal();
            if (IS_MANAGER) {
                closeEditModal();
                closeCreateModal();
            }
        }
    });
});
</script>

<style>
    .fc { font-family: inherit; }
    .fc .fc-toolbar-title { font-size: 1.25rem; font-weight: 700; }
    .fc .fc-button { font-size: 0.8rem; padding: 0.35rem 0.75rem; }
    .fc .fc-button-primary { background-color: #3b82f6; border-color: #3b82f6; }
    .fc .fc-button-primary:hover { background-color: #2563eb; border-color: #2563eb; }
    .fc .fc-button-primary:not(:disabled).fc-button-active { background-color: #1d4ed8; border-color: #1d4ed8; }
    .fc .fc-event { cursor: pointer; border-radius: 6px; padding: 2px 6px; font-size: 0.8rem; }
    .fc .fc-daygrid-day:hover { background-color: #f9fafb; }
    .fc .fc-day-today { background-color: #eff6ff !important; }
    @if($isManager)
    .fc .fc-daygrid-day { cursor: pointer; }
    @endif
</style>
@endsection