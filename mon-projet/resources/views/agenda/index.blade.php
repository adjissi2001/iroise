@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-7xl mx-auto">

        {{-- ── En-tête ────────────────────────────────────────────────── --}}
<div class="mb-6">
    <div class="text-center mb-4">
        <h1 style="font-size: 1.8rem; font-weight: 900; color: #1f2937; text-align: center;">Agenda des missions</h1>
        @if($isManager)
            <p style="text-align: center;" class="text-gray-500 text-sm mt-1">Cliquez sur une date pour ajouter une mission, sur une mission pour la gérer</p>
        @else
            <p style="text-align: center;" class="text-gray-500 text-sm mt-1">Cliquez sur une mission disponible pour la prendre en charge</p>
        @endif
    </div>
    <div class="flex items-center justify-center gap-3">
        {{-- Légende --}}
        <div style="display:flex; align-items:center; justify-content:center; gap:0.5rem; flex-wrap:wrap;">
            <span style="display:inline-block; background:#f97316; color:#fff; padding:5px 14px; border-radius:8px; font-size:0.85rem; font-weight:700;">Disponible</span>
            <span style="display:inline-block; background:#3b82f6; color:#fff; padding:5px 14px; border-radius:8px; font-size:0.85rem; font-weight:700;">Prise</span>
            <span style="display:inline-block; background:#10b981; color:#fff; padding:5px 14px; border-radius:8px; font-size:0.85rem; font-weight:700;">Validée</span>
            <span style="display:inline-block; background:#881337; color:#fff; padding:5px 14px; border-radius:8px; font-size:0.85rem; font-weight:700;">Annulée</span>
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
<div id="toastContainer" style="position:fixed; top:1rem; right:1rem; z-index:99999; display:flex; flex-direction:column; gap:0.5rem;"></div>

{{-- ══════════════════════════════════════════════════════════════════════ --}}
{{-- MODAL détail mission                                                   --}}
{{-- ══════════════════════════════════════════════════════════════════════ --}}
<div id="missionModal" style="display:none; position:fixed; inset:0; align-items:center; justify-content:center; padding:2rem 1rem; background:rgba(0,0,0,0.32); backdrop-filter:blur(4px); z-index:60;">
    <div id="modalOverlay" style="position:absolute; inset:0;" onclick="document.getElementById('missionModal').style.display='none';"></div>
    <div id="modalBox" style="background:rgba(243,244,246,0.98); border-radius:12px; box-shadow:0 20px 40px rgba(15,23,42,0.25); padding:1.5rem 2rem; max-width:420px; width:90vw; position:relative; border:1px solid rgba(15,23,42,0.04);">
        {{-- Bouton fermer --}}
        <button id="modalClose" style="position:absolute; right:14px; top:12px; background:transparent; border:none; font-size:1.5rem; line-height:1; color:rgba(15,23,42,0.6); cursor:pointer;">&times;</button>

        {{-- Titre --}}
        <h2 id="modalTitle" style="font-size:1.3rem; font-weight:800; color:#0f172a; margin:0 0 1rem 0;"></h2>

        {{-- Infos --}}
        <div style="display:flex; flex-direction:column; gap:0.5rem; font-size:0.9rem; color:#374151;">
            <div id="modalDate" style="display:flex; align-items:center; gap:0.5rem;">
                <svg style="width:16px;height:16px;color:#6b7280;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span></span>
            </div>
            <div id="modalTime" style="display:flex; align-items:center; gap:0.5rem;">
                <svg style="width:16px;height:16px;color:#6b7280;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span></span>
            </div>
            <div id="modalCommune" style="display:none; align-items:center; gap:0.5rem;">
                <svg style="width:16px;height:16px;color:#6b7280;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span></span>
            </div>
            <div id="modalDescription" style="display:flex; align-items:flex-start; gap:0.5rem;">
                <svg style="width:16px;height:16px;color:#6b7280;flex-shrink:0;margin-top:2px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                <span></span>
            </div>
            <div id="modalBenevole" style="display:none; align-items:center; gap:0.5rem;">
                <svg style="width:16px;height:16px;color:#6b7280;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                <span></span>
            </div>
            <div id="modalBeneficiaire" style="display:none; align-items:center; gap:0.5rem;">
                <svg style="width:16px;height:16px;color:#6b7280;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span></span>
            </div>
            <div id="modalEtat" style="display:flex; align-items:center; gap:0.5rem;">
                <span style="font-weight:700; color:#111827;">État :</span>
                <span id="modalEtatBadge" style="display:inline-block; padding:4px 14px; border-radius:9999px; font-size:0.85rem; font-weight:700; color:#fff;"></span>
            </div>
        </div>

        {{-- Boutons d'action --}}
        <div id="modalActions" style="margin-top:1rem; display:flex; align-items:center; gap:0.5rem; flex-wrap:wrap;">
            @if($isManager)
            <button id="btnModifier" style="display:inline-flex; align-items:center; justify-content:center; width:36px; height:36px; background:#eff6ff; border:1px solid #bfdbfe; border-radius:8px; cursor:pointer; color:#2563eb;" title="Modifier">
                <svg style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            </button>
            <button id="btnAnnuler" style="display:inline-flex; align-items:center; justify-content:center; width:36px; height:36px; background:#fef2f2; border:1px solid #fecaca; border-radius:8px; cursor:pointer; color:#dc2626;" title="Annuler la mission">
                <svg style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
            </button>
            @endif
            <button id="btnPrendre" style="display:none; align-items:center; gap:0.375rem; padding:0.5rem 1rem; background:#059669; color:#fff; border-radius:8px; border:none; cursor:pointer; font-weight:700; font-size:0.9rem;">
                ✋ Prendre
            </button>
            <button id="btnRetirer" style="display:none; align-items:center; gap:0.375rem; padding:0.5rem 1rem; background:transparent; color:#ea580c; border:1px solid #fed7aa; border-radius:8px; cursor:pointer; font-weight:600; font-size:0.9rem;">
                Retirer
            </button>
        </div>
    </div>
</div>

@if($isManager)
{{-- ══════════════════════════════════════════════════════════════════════ --}}
{{-- MODAL création mission                                                 --}}
{{-- ══════════════════════════════════════════════════════════════════════ --}}
<div id="createModal" style="display:none; position:fixed; inset:0; align-items:center; justify-content:center; padding:2rem 1rem; background:rgba(0,0,0,0.32); backdrop-filter:blur(4px); z-index:60;">
    <div id="createOverlay" style="position:absolute; inset:0;" onclick="closeCreateModal()"></div>
    <div style="background:rgba(243,244,246,0.98); border-radius:12px; box-shadow:0 20px 40px rgba(15,23,42,0.25); padding:2rem 2.25rem; max-width:560px; width:90vw; max-height:85vh; overflow-y:auto; position:relative; border:1px solid rgba(15,23,42,0.04);">
        <button onclick="closeCreateModal()" style="position:absolute; right:14px; top:12px; background:transparent; border:none; font-size:1.5rem; line-height:1; color:rgba(15,23,42,0.6); cursor:pointer;">&times;</button>
        <h2 style="font-size:1.5rem; font-weight:800; text-align:center; color:#0f172a; margin:0 0 1.25rem 0;">Ajouter une mission</h2>

        <form id="createForm">
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:0.75rem;">
                <div>
                    <label style="display:block; margin-bottom:0.4rem; font-weight:700; font-size:0.95rem; color:#111827;">Catégorie</label>
                    <select id="create_categorie" style="width:100%; padding:0.6rem 0.8rem; border-radius:8px; border:1px solid rgba(15,23,42,0.12); background:#fff; font-size:0.9rem; color:#0f172a;">
                        <option value="">-- sélectionner --</option>
                        @foreach(($categories ?? collect()) as $c)
                            <option value="{{ $c->id_categorie }}">{{ $c->nom_categorie }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="display:block; margin-bottom:0.4rem; font-weight:700; font-size:0.95rem; color:#111827;">Commune</label>
                    <select id="create_commune" style="width:100%; padding:0.6rem 0.8rem; border-radius:8px; border:1px solid rgba(15,23,42,0.12); background:#fff; font-size:0.9rem; color:#0f172a;">
                        <option value="">-- sélectionner --</option>
                        <option value="SAINT RENAN">SAINT RENAN</option>
                        <option value="LANRIVOARE">LANRIVOARE</option>
                        <option value="BREST">BREST</option>
                        <option value="GUILERS">GUILERS</option>
                        <option value="PLOUDALMEZEAU">PLOUDALMEZEAU</option>
                        <option value="PLOUZANE">PLOUZANE</option>
                        <option value="LAMPAUL-PLOUARZEL">LAMPAUL-PLOUARZEL</option>
                        <option value="BOHARS">BOHARS</option>
                        <option value="PLOUARZEL">PLOUARZEL</option>
                        <option value="LOCMARIA-PLOUZANE">LOCMARIA-PLOUZANE</option>
                        <option value="GUIPAVAS">GUIPAVAS</option>
                    </select>
                </div>
            </div>

            <div style="margin-bottom:0.75rem;">
                <label style="display:block; margin-bottom:0.4rem; font-weight:700; font-size:0.95rem; color:#111827;">Lieu</label>
                <input type="text" id="create_lieu" placeholder="Ex: Brest centre" style="width:100%; padding:0.6rem 0.8rem; border-radius:8px; border:1px solid rgba(15,23,42,0.12); background:#fff; font-size:0.9rem; color:#0f172a; box-sizing:border-box;">
            </div>

            <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:1rem; margin-bottom:0.75rem;">
                <div>
                    <label style="display:block; margin-bottom:0.4rem; font-weight:700; font-size:0.95rem; color:#111827;">Date départ *</label>
                    <input type="date" id="create_date_depart" required style="width:100%; padding:0.6rem 0.8rem; border-radius:8px; border:1px solid rgba(15,23,42,0.12); background:#fff; font-size:0.9rem; color:#0f172a; box-sizing:border-box;">
                </div>
                <div>
                    <label style="display:block; margin-bottom:0.4rem; font-weight:700; font-size:0.95rem; color:#111827;">Heure départ</label>
                    <input type="time" id="create_heure_depart" style="width:100%; padding:0.6rem 0.8rem; border-radius:8px; border:1px solid rgba(15,23,42,0.12); background:#fff; font-size:0.9rem; color:#0f172a; box-sizing:border-box;">
                </div>
                <div>
                    <label style="display:block; margin-bottom:0.4rem; font-weight:700; font-size:0.95rem; color:#111827;">Heure arrivée</label>
                    <input type="time" id="create_heure_arrivee" style="width:100%; padding:0.6rem 0.8rem; border-radius:8px; border:1px solid rgba(15,23,42,0.12); background:#fff; font-size:0.9rem; color:#0f172a; box-sizing:border-box;">
                </div>
            </div>

            <div style="margin-bottom:0.75rem;">
                <label style="display:block; margin-bottom:0.4rem; font-weight:700; font-size:0.95rem; color:#111827;">Bénéficiaire *</label>
                <div style="position:relative;">
                    <input type="text" id="create_beneficiaire_search" autocomplete="off" placeholder="Tapez un nom pour rechercher..." style="width:100%; padding:0.6rem 0.8rem; border-radius:8px; border:1px solid rgba(15,23,42,0.12); background:#fff; font-size:0.9rem; color:#0f172a; box-sizing:border-box;">
                    <div id="create_beneficiaire_dropdown" style="display:none; position:absolute; top:100%; left:0; right:0; max-height:180px; overflow-y:auto; background:#fff; border:1px solid #d1d5db; border-radius:0.5rem; box-shadow:0 4px 12px rgba(0,0,0,0.1); z-index:100;"></div>
                </div>
                <div id="create_beneficiaire_selected" style="margin-top:0.5rem; display:flex; flex-wrap:wrap; gap:0.375rem;"></div>
            </div>

            <div style="margin-bottom:0.75rem;">
                <label style="display:block; margin-bottom:0.4rem; font-weight:700; font-size:0.95rem; color:#111827;">Assigner à un bénévole</label>
                <select id="create_benevole" style="width:100%; padding:0.6rem 0.8rem; border-radius:8px; border:1px solid rgba(15,23,42,0.12); background:#fff; font-size:0.9rem; color:#0f172a;">
                    <option value="">-- non assignée --</option>
                    @foreach($benevoles as $b)
                        <option value="{{ $b->id }}">{{ optional($b->profil)->prenom }} {{ optional($b->profil)->nom }} ({{ $b->email }})</option>
                    @endforeach
                </select>
            </div>

            <div style="margin-bottom:0.75rem;">
                <label style="display:block; margin-bottom:0.4rem; font-weight:700; font-size:0.95rem; color:#111827;">Remarques</label>
                <textarea id="create_description" rows="2" placeholder="Description de la mission..." style="width:100%; padding:0.6rem 0.8rem; border-radius:8px; border:1px solid rgba(15,23,42,0.12); background:#fff; font-size:0.9rem; color:#0f172a; box-sizing:border-box; resize:vertical;"></textarea>
            </div>

            <div style="display:flex; justify-content:flex-end; gap:0.75rem; padding-top:0.5rem;">
                <button type="button" onclick="closeCreateModal()" style="background:transparent; border:1px solid rgba(15,23,42,0.08); color:#0f172a; padding:0.55rem 0.9rem; border-radius:8px; font-weight:600; font-size:0.9rem; cursor:pointer;">Annuler</button>
                <button type="submit" id="createSubmitBtn" style="background:linear-gradient(180deg,#0b63ff,#084fcf); color:#fff; border:none; padding:0.6rem 1.1rem; border-radius:8px; font-weight:700; font-size:0.9rem; cursor:pointer;">Enregistrer</button>
            </div>
        </form>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════════════ --}}
{{-- MODAL édition mission                                                  --}}
{{-- ══════════════════════════════════════════════════════════════════════ --}}
<div id="editModal" style="display:none; position:fixed; inset:0; align-items:center; justify-content:center; padding:2rem 1rem; background:rgba(0,0,0,0.32); backdrop-filter:blur(4px); z-index:60;">
    <div id="editOverlay" style="position:absolute; inset:0;" onclick="closeEditModal()"></div>
    <div style="background:rgba(243,244,246,0.98); border-radius:12px; box-shadow:0 20px 40px rgba(15,23,42,0.25); padding:2rem 2.25rem; max-width:560px; width:90vw; max-height:85vh; overflow-y:auto; position:relative; border:1px solid rgba(15,23,42,0.04);">
        <button onclick="closeEditModal()" style="position:absolute; right:14px; top:12px; background:transparent; border:none; font-size:1.5rem; line-height:1; color:rgba(15,23,42,0.6); cursor:pointer;">&times;</button>
        <h2 style="font-size:1.5rem; font-weight:800; text-align:center; color:#0f172a; margin:0 0 1.25rem 0;">Modifier la mission</h2>

        <form id="editForm">
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:0.75rem;">
                <div>
                    <label style="display:block; margin-bottom:0.4rem; font-weight:700; font-size:0.95rem; color:#111827;">Catégorie</label>
                    <select id="edit_categorie" style="width:100%; padding:0.6rem 0.8rem; border-radius:8px; border:1px solid rgba(15,23,42,0.12); background:#fff; font-size:0.9rem; color:#0f172a;">
                        <option value="">-- sélectionner --</option>
                        @foreach(($categories ?? collect()) as $c)
                            <option value="{{ $c->id_categorie }}">{{ $c->nom_categorie }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="display:block; margin-bottom:0.4rem; font-weight:700; font-size:0.95rem; color:#111827;">Commune</label>
                    <select id="edit_commune" style="width:100%; padding:0.6rem 0.8rem; border-radius:8px; border:1px solid rgba(15,23,42,0.12); background:#fff; font-size:0.9rem; color:#0f172a;">
                        <option value="">-- sélectionner --</option>
                        <option value="SAINT RENAN">SAINT RENAN</option>
                        <option value="LANRIVOARE">LANRIVOARE</option>
                        <option value="BREST">BREST</option>
                        <option value="GUILERS">GUILERS</option>
                        <option value="PLOUDALMEZEAU">PLOUDALMEZEAU</option>
                        <option value="PLOUZANE">PLOUZANE</option>
                        <option value="LAMPAUL-PLOUARZEL">LAMPAUL-PLOUARZEL</option>
                        <option value="BOHARS">BOHARS</option>
                        <option value="PLOUARZEL">PLOUARZEL</option>
                        <option value="LOCMARIA-PLOUZANE">LOCMARIA-PLOUZANE</option>
                        <option value="GUIPAVAS">GUIPAVAS</option>
                    </select>
                </div>
            </div>

            <div style="margin-bottom:0.75rem;">
                <label style="display:block; margin-bottom:0.4rem; font-weight:700; font-size:0.95rem; color:#111827;">Lieu</label>
                <input type="text" id="edit_lieu" style="width:100%; padding:0.6rem 0.8rem; border-radius:8px; border:1px solid rgba(15,23,42,0.12); background:#fff; font-size:0.9rem; color:#0f172a; box-sizing:border-box;">
            </div>

            <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:1rem; margin-bottom:0.75rem;">
                <div>
                    <label style="display:block; margin-bottom:0.4rem; font-weight:700; font-size:0.95rem; color:#111827;">Date départ</label>
                    <input type="date" id="edit_date_depart" style="width:100%; padding:0.6rem 0.8rem; border-radius:8px; border:1px solid rgba(15,23,42,0.12); background:#fff; font-size:0.9rem; color:#0f172a; box-sizing:border-box;">
                </div>
                <div>
                    <label style="display:block; margin-bottom:0.4rem; font-weight:700; font-size:0.95rem; color:#111827;">Heure départ</label>
                    <input type="time" id="edit_heure_depart" style="width:100%; padding:0.6rem 0.8rem; border-radius:8px; border:1px solid rgba(15,23,42,0.12); background:#fff; font-size:0.9rem; color:#0f172a; box-sizing:border-box;">
                </div>
                <div>
                    <label style="display:block; margin-bottom:0.4rem; font-weight:700; font-size:0.95rem; color:#111827;">Heure arrivée</label>
                    <input type="time" id="edit_heure_arrivee" style="width:100%; padding:0.6rem 0.8rem; border-radius:8px; border:1px solid rgba(15,23,42,0.12); background:#fff; font-size:0.9rem; color:#0f172a; box-sizing:border-box;">
                </div>
            </div>

            <div style="margin-bottom:0.75rem;">
                <label style="display:block; margin-bottom:0.4rem; font-weight:700; font-size:0.95rem; color:#111827;">Bénéficiaire *</label>
                <div style="position:relative;">
                    <input type="text" id="edit_beneficiaire_search" autocomplete="off" placeholder="Tapez un nom pour rechercher..." style="width:100%; padding:0.6rem 0.8rem; border-radius:8px; border:1px solid rgba(15,23,42,0.12); background:#fff; font-size:0.9rem; color:#0f172a; box-sizing:border-box;">
                    <div id="edit_beneficiaire_dropdown" style="display:none; position:absolute; top:100%; left:0; right:0; max-height:180px; overflow-y:auto; background:#fff; border:1px solid #d1d5db; border-radius:0.5rem; box-shadow:0 4px 12px rgba(0,0,0,0.1); z-index:100;"></div>
                </div>
                <div id="edit_beneficiaire_selected" style="margin-top:0.5rem; display:flex; flex-wrap:wrap; gap:0.375rem;"></div>
            </div>

            <div style="margin-bottom:0.75rem;">
                <label style="display:block; margin-bottom:0.4rem; font-weight:700; font-size:0.95rem; color:#111827;">Assigner à un bénévole</label>
                <select id="edit_benevole" style="width:100%; padding:0.6rem 0.8rem; border-radius:8px; border:1px solid rgba(15,23,42,0.12); background:#fff; font-size:0.9rem; color:#0f172a;">
                    <option value="">-- non assignée --</option>
                    @foreach($benevoles as $b)
                        <option value="{{ $b->id }}">{{ optional($b->profil)->prenom }} {{ optional($b->profil)->nom }} ({{ $b->email }})</option>
                    @endforeach
                </select>
            </div>

            <div style="margin-bottom:0.75rem;">
                <label style="display:block; margin-bottom:0.4rem; font-weight:700; font-size:0.95rem; color:#111827;">Remarques</label>
                <textarea id="edit_description" rows="2" style="width:100%; padding:0.6rem 0.8rem; border-radius:8px; border:1px solid rgba(15,23,42,0.12); background:#fff; font-size:0.9rem; color:#0f172a; box-sizing:border-box; resize:vertical;"></textarea>
            </div>

            <div style="margin-bottom:0.75rem;">
                <label style="display:block; margin-bottom:0.4rem; font-weight:700; font-size:0.95rem; color:#111827;">État</label>
                <select id="edit_etat" style="width:100%; padding:0.6rem 0.8rem; border-radius:8px; border:1px solid rgba(15,23,42,0.12); background:#fff; font-size:0.9rem; color:#0f172a;">
                    <option value="non_prise">Disponible</option>
                    <option value="prise">Prise</option>
                    <option value="validee">Validée</option>
                    <option value="annulee">Annulée</option>
                </select>
            </div>

            <div style="display:flex; justify-content:flex-end; gap:0.75rem; padding-top:0.5rem;">
                <button type="button" onclick="closeEditModal()" style="background:transparent; border:1px solid rgba(15,23,42,0.08); color:#0f172a; padding:0.55rem 0.9rem; border-radius:8px; font-weight:600; font-size:0.9rem; cursor:pointer;">Annuler</button>
                <button type="submit" id="editSubmitBtn" style="background:linear-gradient(180deg,#0b63ff,#084fcf); color:#fff; border:none; padding:0.6rem 1.1rem; border-radius:8px; font-weight:700; font-size:0.9rem; cursor:pointer;">Enregistrer</button>
            </div>
        </form>
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

    // ── Données bénéficiaires ───────────────────────────────────────────
    const allBeneficiaires = @json(($beneficiaires ?? collect())->map(fn($b) => ['id' => $b->id_beneficiaire, 'nom' => $b->nom, 'prenom' => $b->prenom]));

    // ── Système de recherche bénéficiaire ────────────────────────────────
    function setupBeneficiaireSearch(prefix) {
        const searchInput = document.getElementById(prefix + '_beneficiaire_search');
        const dropdown = document.getElementById(prefix + '_beneficiaire_dropdown');
        const selectedContainer = document.getElementById(prefix + '_beneficiaire_selected');
        let selectedIds = [];

        function getSelectedIds() { return selectedIds; }
        function setSelectedIds(ids) {
            selectedIds = ids;
            renderSelected();
        }

        function renderDropdown(filter) {
            const term = filter.toLowerCase().trim();
            if (!term) { dropdown.style.display = 'none'; return; }
            const matches = allBeneficiaires.filter(b =>
                !selectedIds.includes(b.id) &&
                ((b.prenom + ' ' + b.nom).toLowerCase().includes(term) || (b.nom + ' ' + b.prenom).toLowerCase().includes(term))
            );
            if (matches.length === 0) {
                dropdown.innerHTML = '<div style="padding:8px 12px; color:#9ca3af; font-size:0.8rem;">Aucun résultat</div>';
            } else {
                dropdown.innerHTML = matches.map(b =>
                    '<div data-id="' + b.id + '" style="padding:8px 12px; cursor:pointer; font-size:0.875rem; transition:background 0.1s;" onmouseover="this.style.background=\'#f3f4f6\'" onmouseout="this.style.background=\'#fff\'">' + b.prenom + ' ' + b.nom + '</div>'
                ).join('');
                dropdown.querySelectorAll('[data-id]').forEach(el => {
                    el.addEventListener('click', function() {
                        const id = parseInt(this.dataset.id);
                        if (!selectedIds.includes(id)) {
                            selectedIds.push(id);
                            renderSelected();
                        }
                        searchInput.value = '';
                        dropdown.style.display = 'none';
                    });
                });
            }
            dropdown.style.display = 'block';
        }

        function renderSelected() {
            selectedContainer.innerHTML = selectedIds.map(id => {
                const b = allBeneficiaires.find(x => x.id === id);
                if (!b) return '';
                return '<span style="display:inline-flex; align-items:center; gap:4px; background:#e0f2fe; color:#0369a1; padding:2px 8px; border-radius:9999px; font-size:0.75rem; font-weight:500;">'
                    + b.prenom + ' ' + b.nom
                    + '<button type="button" data-remove="' + id + '" style="background:none; border:none; cursor:pointer; color:#0369a1; font-weight:bold; font-size:0.85rem; line-height:1; padding:0 2px;">&times;</button>'
                    + '</span>';
            }).join('');
            selectedContainer.querySelectorAll('[data-remove]').forEach(btn => {
                btn.addEventListener('click', function() {
                    selectedIds = selectedIds.filter(x => x !== parseInt(this.dataset.remove));
                    renderSelected();
                });
            });
        }

        searchInput.addEventListener('input', function() { renderDropdown(this.value); });
        searchInput.addEventListener('focus', function() { if (this.value.trim()) renderDropdown(this.value); });
        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !dropdown.contains(e.target)) dropdown.style.display = 'none';
        });

        return { getSelectedIds, setSelectedIds, clear: function() { selectedIds = []; selectedContainer.innerHTML = ''; searchInput.value = ''; } };
    }

    let createBenefSearch = null;
    let editBenefSearch = null;
    if (IS_MANAGER) {
        createBenefSearch = setupBeneficiaireSearch('create');
        editBenefSearch = setupBeneficiaireSearch('edit');
    }

    // ── Helpers ──────────────────────────────────────────────────────────
    function showToast(message, type = 'success') {
        const container = document.getElementById('toastContainer');
        const bgColors = { success: '#059669', error: '#dc2626', info: '#2563eb' };
        const bg = bgColors[type] || bgColors.info;
        const toast = document.createElement('div');
        toast.style.cssText = 'background:' + bg + '; color:#fff; padding:12px 20px; border-radius:8px; box-shadow:0 4px 12px rgba(0,0,0,0.15); font-size:0.875rem; font-weight:500; transform:translateX(100%); transition:transform 0.3s ease;';
        toast.textContent = message;
        container.appendChild(toast);
        requestAnimationFrame(function() { toast.style.transform = 'translateX(0)'; });
        setTimeout(function() { toast.style.transform = 'translateX(100%)'; setTimeout(function() { toast.remove(); }, 300); }, 3000);
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
        eventDisplay: 'block',

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

            // Afficher la commune
            const communeDiv = document.getElementById('modalCommune');
            if (props.commune && props.commune.trim()) {
                communeDiv.style.display = 'flex';
                communeDiv.querySelector('span').textContent = 'Commune : ' + props.commune;
            } else {
                communeDiv.style.display = 'none';
            }

            document.querySelector('#modalDescription span').textContent = props.description || 'Aucune description';

            // Afficher le bénévole assigné
            const benevoleDiv = document.getElementById('modalBenevole');
            if (props.benevole_name && props.benevole_name.trim()) {
                benevoleDiv.style.display = 'flex';
                benevoleDiv.querySelector('span').textContent = 'Bénévole : ' + props.benevole_name;
            } else {
                benevoleDiv.style.display = 'none';
            }

            // Afficher le(s) bénéficiaire(s)
            const beneficiaireDiv = document.getElementById('modalBeneficiaire');
            if (props.beneficiaire_names && props.beneficiaire_names.trim()) {
                beneficiaireDiv.style.display = 'flex';
                beneficiaireDiv.querySelector('span').textContent = 'Bénéficiaire(s) : ' + props.beneficiaire_names;
            } else {
                beneficiaireDiv.style.display = 'none';
            }

            const etatColors = { 'non_prise': '#f97316', 'prise': '#3b82f6', 'validee': '#10b981', 'annulee': '#881337' };
            const badge = document.getElementById('modalEtatBadge');
            badge.textContent = etatLabels[props.etat] || props.etat;
            badge.style.backgroundColor = etatColors[props.etat] || '#6b7280';

            // Afficher/masquer les boutons selon le rôle et l'état
            const isAnnulee = props.etat === 'annulee';

            if (IS_MANAGER) {
                btnModifier.style.display = isAnnulee ? 'none' : 'inline-flex';
                btnAnnuler.style.display = isAnnulee ? 'none' : 'inline-flex';
            }

            // Tout le monde peut prendre/retirer une mission
            btnPrendre.style.display = props.can_take ? 'inline-flex' : 'none';
            btnRetirer.style.display = props.is_mine ? 'inline-flex' : 'none';

            modal.style.display = 'flex';
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
    function closeModal() { modal.style.display = 'none'; }
    modalClose.addEventListener('click', function() { closeModal(); currentEvent = null; });
    modalOverlay.addEventListener('click', function() { closeModal(); currentEvent = null; });

    // ── Modifier → ouvrir le formulaire d'édition (managers seulement) ──
    if (IS_MANAGER && btnModifier) {
        btnModifier.addEventListener('click', function () {
            if (!currentEvent) return;
            const props = currentEvent.props;

            document.getElementById('edit_categorie').value = props.id_categorie || '';
            document.getElementById('edit_commune').value = props.commune || '';
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

            // Charger les bénéficiaires existants de cette mission
            if (editBenefSearch) {
                editBenefSearch.setSelectedIds(props.beneficiaire_ids || []);
            }

            closeModal();
            document.getElementById('editModal').style.display = 'flex';
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
            document.getElementById('editModal').style.display = 'none';
        };

        document.getElementById('editOverlay').addEventListener('click', closeEditModal);

        document.getElementById('editForm').addEventListener('submit', async function (e) {
            e.preventDefault();
            if (!currentEvent) return;

            const submitBtn = document.getElementById('editSubmitBtn');
            submitBtn.disabled = true;
            submitBtn.textContent = 'Enregistrement...';

            const editBenefIds = editBenefSearch ? editBenefSearch.getSelectedIds() : [];
            if (editBenefIds.length === 0) {
                showToast('Veuillez sélectionner au moins un bénéficiaire.', 'error');
                submitBtn.disabled = false;
                submitBtn.textContent = 'Enregistrer';
                return;
            }

            const payload = {
                categorie: document.getElementById('edit_categorie').value || null,
                commune: document.getElementById('edit_commune').value || null,
                lieu: document.getElementById('edit_lieu').value || null,
                date_depart: document.getElementById('edit_date_depart').value,
                heure_depart: document.getElementById('edit_heure_depart').value || null,
                heure_arrivee: document.getElementById('edit_heure_arrivee').value || null,
                description: document.getElementById('edit_description').value || null,
                etat: document.getElementById('edit_etat').value,
                benevole_id: document.getElementById('edit_benevole').value || null,
                beneficiaire_ids: editBenefIds,
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
            document.getElementById('create_commune').value = '';
            document.getElementById('create_lieu').value = '';
            document.getElementById('create_date_depart').value = dateStr || '';
            document.getElementById('create_heure_depart').value = '';
            document.getElementById('create_heure_arrivee').value = '';
            document.getElementById('create_description').value = '';
            document.getElementById('create_benevole').value = '';
            if (createBenefSearch) createBenefSearch.clear();
            document.getElementById('createModal').style.display = 'flex';
        };

        window.closeCreateModal = function () {
            document.getElementById('createModal').style.display = 'none';
        };

        document.getElementById('createOverlay').addEventListener('click', closeCreateModal);

        document.getElementById('createForm').addEventListener('submit', async function (e) {
            e.preventDefault();
            const submitBtn = document.getElementById('createSubmitBtn');
            submitBtn.disabled = true;
            submitBtn.textContent = 'Enregistrement...';

            const createBenefIds = createBenefSearch ? createBenefSearch.getSelectedIds() : [];
            if (createBenefIds.length === 0) {
                showToast('Veuillez sélectionner au moins un bénéficiaire.', 'error');
                submitBtn.disabled = false;
                submitBtn.textContent = 'Enregistrer';
                return;
            }

            const payload = {
                categorie: document.getElementById('create_categorie').value || null,
                commune: document.getElementById('create_commune').value || null,
                lieu: document.getElementById('create_lieu').value || null,
                date_depart: document.getElementById('create_date_depart').value,
                heure_depart: document.getElementById('create_heure_depart').value || null,
                heure_arrivee: document.getElementById('create_heure_arrivee').value || null,
                description: document.getElementById('create_description').value || null,
                benevole_id: document.getElementById('create_benevole').value || null,
                beneficiaire_ids: createBenefIds,
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

    /* ── Événements bien visibles ── */
    .fc .fc-event {
        cursor: pointer;
        border-radius: 4px;
        padding: 2px 4px;
        font-size: 0.8rem;
        font-weight: 600;
        border-left-width: 4px !important;
        border-top-width: 1px !important;
        border-right-width: 1px !important;
        border-bottom-width: 1px !important;
        box-shadow: none;
        transition: transform 0.15s ease;
        margin-bottom: 1px;
        line-height: 1.3;
    }
    .fc .fc-event:hover {
        transform: scale(1.02);
        box-shadow: 0 2px 4px rgba(0,0,0,0.15);
    }

    /* Vue semaine/jour: bloc plus grand */
    .fc .fc-timegrid-event .fc-event-main {
        padding: 4px 6px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    /* Vue liste: bande colorée à gauche */
    .fc .fc-list-event-dot {
        width: 12px;
        height: 12px;
        border-width: 3px;
    }

    .fc .fc-daygrid-day:hover { background-color: #f0f7ff; }
    .fc .fc-day-today { background-color: #eff6ff !important; }

    /* Petits marqueurs de point coloré sous la date quand dayMaxEvents est atteint */
    .fc .fc-daygrid-more-link {
        font-weight: 700;
        color: #2563eb;
    }
    @if($isManager)
    .fc .fc-daygrid-day { cursor: pointer; }
    @endif
</style>
@endsection