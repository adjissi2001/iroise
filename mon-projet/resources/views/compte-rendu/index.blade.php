@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-7xl mx-auto">

        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Comptes rendus de missions</h1>
            <p class="text-gray-500 text-sm mt-1">Complétez les informations manquantes (départ, arrivée, km) pour valider la mission</p>
        </div>

        @if($comptesRendus->isEmpty())
            <div style="text-align:center; padding:3rem 1rem; color:#6b7280; font-size:1.1rem;">
                <svg style="width:48px;height:48px;margin:0 auto 1rem;color:#d1d5db;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <p>Aucun compte rendu pour le moment.</p>
            </div>
        @else
            <div style="overflow-x:auto;">
                <table style="width:100%; border-collapse:separate; border-spacing:0; background:#fff; border-radius:12px; box-shadow:0 1px 6px rgba(0,0,0,0.08); overflow:hidden;">
                    <thead>
                        <tr style="background:linear-gradient(135deg,#1e3a5f,#2563eb); color:#fff;">
                            <th style="padding:12px 16px; text-align:left; font-size:0.85rem; font-weight:700;">Date</th>
                            <th style="padding:12px 16px; text-align:left; font-size:0.85rem; font-weight:700;">Lieu</th>
                            <th style="padding:12px 16px; text-align:left; font-size:0.85rem; font-weight:700;">Commune</th>
                            <th style="padding:12px 16px; text-align:left; font-size:0.85rem; font-weight:700;">Bénéficiaire(s)</th>
                            <th style="padding:12px 16px; text-align:left; font-size:0.85rem; font-weight:700;">Bénévole</th>
                            <th style="padding:12px 16px; text-align:center; font-size:0.85rem; font-weight:700;">Départ réel</th>
                            <th style="padding:12px 16px; text-align:center; font-size:0.85rem; font-weight:700;">Arrivée réelle</th>
                            <th style="padding:12px 16px; text-align:center; font-size:0.85rem; font-weight:700;">Km</th>
                            <th style="padding:12px 16px; text-align:left; font-size:0.85rem; font-weight:700;">Problèmes</th>
                            <th style="padding:12px 16px; text-align:center; font-size:0.85rem; font-weight:700;">Statut</th>
                            <th style="padding:12px 16px; text-align:center; font-size:0.85rem; font-weight:700;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($comptesRendus as $i => $cr)
                        @php
                            $m = $cr->mission;
                            $isComplete = $cr->heure_depart_reel && $cr->heure_arrivee_reel && $cr->kilometrage;
                            $rowBg = $isComplete ? ($i % 2 === 0 ? '#fff' : '#f9fafb') : '#fffbeb';
                            $rowBgHover = $isComplete ? '#eff6ff' : '#fef3c7';
                        @endphp
                        <tr style="border-bottom:1px solid #f3f4f6; background:{{ $rowBg }};" onmouseover="this.style.background='{{ $rowBgHover }}'" onmouseout="this.style.background='{{ $rowBg }}'">
                            <td style="padding:10px 16px; font-size:0.9rem; color:#374151; white-space:nowrap;">{{ $m ? \Carbon\Carbon::parse($m->date_depart)->translatedFormat('d/m/Y') : '-' }}</td>
                            <td style="padding:10px 16px; font-size:0.9rem; color:#374151;">{{ $m->nom_lieu ?? '-' }}</td>
                            <td style="padding:10px 16px; font-size:0.9rem; color:#374151;">{{ $m->commune ?? '-' }}</td>
                            <td style="padding:10px 16px; font-size:0.9rem; color:#374151;">{{ $m ? $m->beneficiaires->map(fn($b) => $b->prenom . ' ' . $b->nom)->implode(', ') : '-' }}</td>
                            <td style="padding:10px 16px; font-size:0.9rem; color:#374151;">{{ $cr->benevole ? optional($cr->benevole->profil)->prenom . ' ' . optional($cr->benevole->profil)->nom : '-' }}</td>
                            <td style="padding:10px 16px; font-size:0.9rem; color:#374151; text-align:center;">{{ $cr->heure_depart_reel ?? '-' }}</td>
                            <td style="padding:10px 16px; font-size:0.9rem; color:#374151; text-align:center;">{{ $cr->heure_arrivee_reel ?? '-' }}</td>
                            <td style="padding:10px 16px; font-size:0.9rem; color:#374151; text-align:center; font-weight:600;">{{ $cr->kilometrage ?? '-' }}</td>
                            <td style="padding:10px 16px; font-size:0.9rem; color:#374151; max-width:200px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;" title="{{ $cr->remarques_problemes }}">{{ $cr->remarques_problemes ?? '-' }}</td>
                            <td style="padding:10px 16px; text-align:center;">
                                @if($isComplete)
                                    <span style="display:inline-block; padding:4px 10px; border-radius:999px; font-size:0.78rem; font-weight:700; background:#dcfce7; color:#166534; border:1px solid #bbf7d0;">Complété</span>
                                @else
                                    <span style="display:inline-block; padding:4px 10px; border-radius:999px; font-size:0.78rem; font-weight:700; background:#fef3c7; color:#92400e; border:1px solid #fde68a;">À compléter</span>
                                @endif
                            </td>
                            <td style="padding:10px 16px; text-align:center;">
                                <button onclick="openCrModal({{ $cr->id_compte_rendu }}, {{ json_encode([
                                    'id' => $cr->id_compte_rendu,
                                    'lieu' => $m->nom_lieu ?? '',
                                    'commune' => $m->commune ?? '',
                                    'date' => $m ? \Carbon\Carbon::parse($m->date_depart)->translatedFormat('d/m/Y') : '',
                                    'beneficiaires' => $m ? $m->beneficiaires->map(fn($b) => $b->prenom . ' ' . $b->nom)->implode(', ') : '',
                                    'kilometrage' => $cr->kilometrage ?? '',
                                    'heure_depart_reel' => $cr->heure_depart_reel ?? '',
                                    'heure_arrivee_reel' => $cr->heure_arrivee_reel ?? '',
                                    'remarques_problemes' => $cr->remarques_problemes ?? '',
                                ], JSON_HEX_APOS | JSON_HEX_QUOT) }})" style="background:{{ $isComplete ? '#eff6ff' : '#fef3c7' }}; border:1px solid {{ $isComplete ? '#bfdbfe' : '#fde68a' }}; border-radius:8px; padding:6px 12px; cursor:pointer; color:{{ $isComplete ? '#2563eb' : '#92400e' }}; font-weight:600; font-size:0.85rem;">
                                    {{ $isComplete ? 'Modifier' : 'Compléter' }}
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════════════ --}}
{{-- MODAL modification compte rendu                                        --}}
{{-- ══════════════════════════════════════════════════════════════════════ --}}
<div id="crModal" style="display:none; position:fixed; inset:0; align-items:center; justify-content:center; padding:2rem 1rem; background:rgba(0,0,0,0.32); backdrop-filter:blur(4px); z-index:60;">
    <div style="position:absolute; inset:0;" onclick="closeCrModal()"></div>
    <div style="background:rgba(243,244,246,0.98); border-radius:12px; box-shadow:0 20px 40px rgba(15,23,42,0.25); padding:2rem 2.25rem; max-width:520px; width:90vw; position:relative; border:1px solid rgba(15,23,42,0.04); margin-top:auto; margin-bottom:auto;">
        <button onclick="closeCrModal()" style="position:absolute; right:14px; top:12px; background:transparent; border:none; font-size:1.5rem; line-height:1; color:rgba(15,23,42,0.6); cursor:pointer;">&times;</button>
        <h2 style="font-size:1.4rem; font-weight:800; text-align:center; color:#0f172a; margin:0 0 1rem 0;">Modifier le compte rendu</h2>

        {{-- Infos mission (lecture seule) --}}
        <div style="background:#f0f9ff; border:1px solid #bae6fd; border-radius:8px; padding:12px 16px; margin-bottom:1rem; font-size:0.88rem; color:#374151;">
            <div><strong>Lieu :</strong> <span id="cr_info_lieu"></span></div>
            <div><strong>Commune :</strong> <span id="cr_info_commune"></span></div>
            <div><strong>Date :</strong> <span id="cr_info_date"></span></div>
            <div><strong>Bénéficiaire(s) :</strong> <span id="cr_info_benef"></span></div>
        </div>

        <form id="crForm">
            <input type="hidden" id="cr_id">

            <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:1rem; margin-bottom:0.75rem;">
                <div>
                    <label style="display:block; margin-bottom:0.4rem; font-weight:700; font-size:0.95rem; color:#111827;">Heure départ</label>
                    <input type="time" id="cr_heure_depart" style="width:100%; padding:0.6rem 0.8rem; border-radius:8px; border:1px solid rgba(15,23,42,0.12); background:#fff; font-size:0.9rem; color:#0f172a; box-sizing:border-box;">
                </div>
                <div>
                    <label style="display:block; margin-bottom:0.4rem; font-weight:700; font-size:0.95rem; color:#111827;">Heure arrivée</label>
                    <input type="time" id="cr_heure_arrivee" style="width:100%; padding:0.6rem 0.8rem; border-radius:8px; border:1px solid rgba(15,23,42,0.12); background:#fff; font-size:0.9rem; color:#0f172a; box-sizing:border-box;">
                </div>
                <div>
                    <label style="display:block; margin-bottom:0.4rem; font-weight:700; font-size:0.95rem; color:#111827;">Kilométrage</label>
                    <input type="text" id="cr_kilometrage" placeholder="Ex: 25" style="width:100%; padding:0.6rem 0.8rem; border-radius:8px; border:1px solid rgba(15,23,42,0.12); background:#fff; font-size:0.9rem; color:#0f172a; box-sizing:border-box;">
                </div>
            </div>

            <div style="margin-bottom:0.75rem;">
                <label style="display:block; margin-bottom:0.4rem; font-weight:700; font-size:0.95rem; color:#111827;">Problèmes rencontrés</label>
                <textarea id="cr_remarques" rows="3" placeholder="Décrivez les éventuels problèmes rencontrés..." style="width:100%; padding:0.6rem 0.8rem; border-radius:8px; border:1px solid rgba(15,23,42,0.12); background:#fff; font-size:0.9rem; color:#0f172a; box-sizing:border-box; resize:vertical;"></textarea>
            </div>

            <div style="display:flex; justify-content:flex-end; gap:0.75rem; padding-top:0.5rem;">
                <button type="button" onclick="closeCrModal()" style="background:transparent; border:1px solid rgba(15,23,42,0.08); color:#0f172a; padding:0.55rem 0.9rem; border-radius:8px; font-weight:600; font-size:0.9rem; cursor:pointer;">Annuler</button>
                <button type="submit" id="crSubmitBtn" style="background:linear-gradient(180deg,#0b63ff,#084fcf); color:#fff; border:none; padding:0.6rem 1.1rem; border-radius:8px; font-weight:700; font-size:0.9rem; cursor:pointer;">Enregistrer</button>
            </div>
        </form>
    </div>
</div>

{{-- Toast container --}}
<div id="toastContainer" style="position:fixed; top:1rem; right:1rem; z-index:99999; display:flex; flex-direction:column; gap:0.5rem;"></div>

<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    function showToast(msg, type) {
        const container = document.getElementById('toastContainer');
        const toast = document.createElement('div');
        const isError = type === 'error';
        toast.style.cssText = 'padding:12px 20px; border-radius:8px; font-size:0.9rem; font-weight:600; color:#fff; box-shadow:0 4px 12px rgba(0,0,0,0.15); transition:opacity 0.3s; max-width:340px;';
        toast.style.background = isError ? '#dc2626' : '#10b981';
        toast.textContent = msg;
        container.appendChild(toast);
        setTimeout(function () { toast.style.opacity = '0'; setTimeout(function () { toast.remove(); }, 300); }, 4000);
    }

    function openCrModal(id, data) {
        document.getElementById('cr_id').value = data.id;
        document.getElementById('cr_info_lieu').textContent = data.lieu || '-';
        document.getElementById('cr_info_commune').textContent = data.commune || '-';
        document.getElementById('cr_info_date').textContent = data.date || '-';
        document.getElementById('cr_info_benef').textContent = data.beneficiaires || '-';
        document.getElementById('cr_kilometrage').value = data.kilometrage || '';
        document.getElementById('cr_heure_depart').value = data.heure_depart_reel || '';
        document.getElementById('cr_heure_arrivee').value = data.heure_arrivee_reel || '';
        document.getElementById('cr_remarques').value = data.remarques_problemes || '';
        document.getElementById('crModal').style.display = 'flex';
    }

    function closeCrModal() {
        document.getElementById('crModal').style.display = 'none';
    }

    document.getElementById('crForm').addEventListener('submit', async function (e) {
        e.preventDefault();
        const id = document.getElementById('cr_id').value;
        const btn = document.getElementById('crSubmitBtn');
        btn.disabled = true;
        btn.textContent = 'Enregistrement...';

        const payload = {
            kilometrage: document.getElementById('cr_kilometrage').value || null,
            heure_depart_reel: document.getElementById('cr_heure_depart').value || null,
            heure_arrivee_reel: document.getElementById('cr_heure_arrivee').value || null,
            remarques_problemes: document.getElementById('cr_remarques').value || null,
        };

        try {
            const resp = await fetch('/compte-rendu/' + id, {
                method: 'PUT',
                headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json', 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            });
            const data = await resp.json();
            if (resp.ok) {
                showToast(data.message || 'Compte rendu mis à jour !');
                closeCrModal();
                setTimeout(function () { location.reload(); }, 800);
            } else {
                showToast(data.error || data.message || 'Erreur', 'error');
            }
        } catch { showToast('Erreur réseau', 'error'); }

        btn.disabled = false;
        btn.textContent = 'Enregistrer';
    });
</script>
@endsection
