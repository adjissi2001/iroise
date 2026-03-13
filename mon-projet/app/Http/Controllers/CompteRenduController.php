<?php

namespace App\Http\Controllers;

use App\Models\CompteRendu;
use App\Models\Mission;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CompteRenduController extends Controller
{
    /**
     * Crée les comptes rendus pour les missions prises dont l'heure de départ est dépassée.
     * La mission reste "prise" jusqu'à ce que le compte rendu soit complété.
     */
    public static function autoValidateMissions(): void
    {
        $now = Carbon::now();

        $missions = Mission::where('etat_mission', 'prise')
            ->whereNotNull('benevole_id')
            ->whereNotNull('heure_depart')
            ->get();

        foreach ($missions as $mission) {
            $missionStart = Carbon::parse($mission->date_depart)
                ->setTimeFromTimeString(Carbon::parse($mission->heure_depart)->format('H:i:s'));

            if ($now->greaterThan($missionStart)) {
                CompteRendu::firstOrCreate(
                    ['id_mission' => $mission->id_mission],
                    [
                        'benevole_id'        => $mission->benevole_id,
                        'heure_depart_reel'  => $mission->heure_depart,
                        'heure_arrivee_reel' => $mission->heure_arrivee,
                    ]
                );
            }
        }
    }

    /**
     * Affiche la liste des comptes rendus.
     */
    public function index()
    {
        // Auto-valider avant d'afficher
        self::autoValidateMissions();

        $user = auth()->user();
        $isManager = $user->is_admin || optional($user->profil)->role === 'referent';

        $query = CompteRendu::with(['mission.beneficiaires', 'benevole.profil']);

        // Les bénévoles ne voient que leurs propres comptes rendus
        if (!$isManager) {
            $query->where('benevole_id', $user->id);
        }

        $comptesRendus = $query->orderByDesc('created_at')->get();

        return view('compte-rendu.index', compact('comptesRendus', 'isManager'));
    }

    /**
     * Met à jour un compte rendu.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $cr = CompteRendu::where('id_compte_rendu', $id)->firstOrFail();

        $user = auth()->user();
        $isManager = $user->is_admin || optional($user->profil)->role === 'referent';

        // Seul le bénévole concerné ou un manager peut modifier
        if (!$isManager && (int) $cr->benevole_id !== (int) $user->id) {
            return response()->json(['error' => 'Action non autorisée.'], 403);
        }

        $validated = $request->validate([
            'kilometrage'        => 'nullable|string|max:20',
            'heure_depart_reel'  => 'nullable',
            'heure_arrivee_reel' => 'nullable',
            'remarques_problemes'=> 'nullable|string|max:2000',
        ]);

        $cr->update($validated);

        // Si le compte rendu est complet, valider la mission
        $cr->refresh();
        if ($cr->heure_depart_reel && $cr->heure_arrivee_reel && $cr->kilometrage) {
            $mission = Mission::find($cr->id_mission);
            if ($mission && $mission->etat_mission !== 'validee') {
                $mission->etat_mission = 'validee';
                $mission->save();
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Compte rendu mis à jour avec succès !',
        ]);
    }
}
