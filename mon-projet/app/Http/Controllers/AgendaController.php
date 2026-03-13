<?php

namespace App\Http\Controllers;

use App\Http\Controllers\CompteRenduController;
use App\Mail\MissionRetraitMail;
use App\Models\Beneficiaire;
use App\Models\Mission;
use App\Models\User;
use App\Services\MissionService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class AgendaController extends Controller
{
    public function __construct(private readonly MissionService $missionService)
    {
    }

    /**
     * Determine if the current user is a manager (admin or referent).
     */
    private function isManager(): bool
    {
        $user = auth()->user();
        return $user->is_admin || optional($user->profil)->role === 'referent';
    }

    public function index()
    {
        $categories = $this->missionService->listCategories();
        $isManager  = $this->isManager();

        // List of bénévoles for the assignment dropdown (managers only)
        $benevoles = $isManager
            ? User::whereHas('profil', fn ($q) => $q->where('role', 'benevole'))
                ->with('profil')
                ->get()
            : collect();

        // List of bénéficiaires for the assignment dropdown
        $beneficiaires = Beneficiaire::where('actif', 1)->orderBy('nom')->get();

        return view('agenda.index', compact('categories', 'isManager', 'benevoles', 'beneficiaires'));
    }

    public function events(Request $request): JsonResponse
    {
        // Auto-valider les missions dépassées
        CompteRenduController::autoValidateMissions();

        $query = Mission::query();

        if ($request->has('start')) {
            $query->where('date_depart', '>=', Carbon::parse($request->start)->toDateString());
        }
        if ($request->has('end')) {
            $query->where('date_depart', '<=', Carbon::parse($request->end)->toDateString());
        }

        $missions = $query->with(['benevole.profil', 'beneficiaires'])->orderBy('date_depart', 'asc')->get();

        $events = $missions->map(function (Mission $mission) {
            $start = $mission->date_depart;
            if ($mission->heure_depart) {
                $start = Carbon::parse($mission->date_depart)->format('Y-m-d') . 'T' . Carbon::parse($mission->heure_depart)->format('H:i:s');
            }

            $end = null;
            if ($mission->heure_arrivee) {
                $end = Carbon::parse($mission->date_depart)->format('Y-m-d') . 'T' . Carbon::parse($mission->heure_arrivee)->format('H:i:s');
            }

            $colors = [
                'non_prise' => ['bg' => '#fff7ed', 'border' => '#f97316', 'text' => '#1f2937'],
                'prise'     => ['bg' => '#eff6ff', 'border' => '#3b82f6', 'text' => '#1f2937'],
                'validee'   => ['bg' => '#ecfdf5', 'border' => '#10b981', 'text' => '#1f2937'],
                'annulee'   => ['bg' => '#fdf2f8', 'border' => '#881337', 'text' => '#1f2937'],
            ];

            $etat = $mission->etat_mission ?? 'non_prise';
            $color = $colors[$etat] ?? $colors['non_prise']; 
            $isMine = $mission->benevole_id && $mission->benevole_id == auth()->id();

            return [
                'id'              => $mission->id_mission,
                'title'           => $mission->nom_lieu ?? 'Mission #' . $mission->id_mission,
                'start'           => $start,
                'end'             => $end,
                'backgroundColor' => $color['bg'],
                'borderColor'     => $color['border'],
                'textColor'       => $color['text'],
                'extendedProps'   => [
                    'description'   => $mission->remarques ?? '',
                    'etat'          => $etat,
                    'lieu'          => $mission->nom_lieu ?? '',
                    'commune'       => $mission->commune ?? '',
                    'heure_depart'  => $mission->heure_depart,
                    'heure_arrivee' => $mission->heure_arrivee,
                    'benevole_id'   => $mission->benevole_id,
                    'benevole_name' => $mission->benevole
                        ? optional($mission->benevole->profil)->prenom . ' ' . optional($mission->benevole->profil)->nom
                        : null,
                    'is_mine'       => $isMine,
                    'can_take'      => $etat === 'non_prise' && !$mission->benevole_id,
                    'id_categorie'  => $mission->id_categorie,
                    'cree_par'      => $mission->cree_par,
                    'beneficiaire_ids' => $mission->beneficiaires->pluck('id_beneficiaire')->toArray(),
                    'beneficiaire_names' => $mission->beneficiaires->map(fn($b) => $b->prenom . ' ' . $b->nom)->implode(', '),
                ],
            ];
        });

        return response()->json($events);
    }

    public function store(Request $request): JsonResponse
    {
        if (!$this->isManager()) {
            return response()->json(['error' => 'Action non autorisée.'], 403);
        }

        $validated = $request->validate([
            'categorie'     => 'nullable|integer',
            'commune'       => 'nullable|string|max:100',
            'lieu'          => 'nullable|string|max:255',
            'date_depart'   => 'required|date',
            'heure_depart'  => 'nullable',
            'heure_arrivee' => 'nullable',
            'description'   => 'nullable|string',
            'benevole_id'       => 'nullable|integer|exists:users,id',
            'beneficiaire_ids'  => 'required|array|min:1',
            'beneficiaire_ids.*'=> 'integer|exists:beneficiaire,id_beneficiaire',
        ]);

        $mission = $this->missionService->createForAdmin(auth()->user(), $validated);

        // Attach bénéficiaires via pivot
        if (!empty($validated['beneficiaire_ids'])) {
            $mission->beneficiaires()->sync($validated['beneficiaire_ids']);
        }

        // If a bénévole was assigned during creation
        if (!empty($validated['benevole_id'])) {
            $mission->benevole_id = $validated['benevole_id'];
            $mission->etat_mission = 'prise';
            $mission->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Mission ajoutée avec succès !',
            'id'      => $mission->id_mission ?? $mission->id,
        ]);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        if (!$this->isManager()) {
            return response()->json(['error' => 'Action non autorisée.'], 403);
        }

        $mission = Mission::where('id_mission', $id)->firstOrFail();

        $validated = $request->validate([
            'categorie'     => 'nullable|integer',
            'commune'       => 'nullable|string|max:100',
            'lieu'          => 'nullable|string|max:255',
            'date_depart'   => 'nullable|date',
            'heure_depart'  => 'nullable',
            'heure_arrivee' => 'nullable',
            'description'   => 'nullable|string',
            'etat'          => 'required|in:non_prise,prise,validee,annulee',
            'benevole_id'       => 'nullable|integer|exists:users,id',
            'beneficiaire_ids'  => 'sometimes|array|min:1',
            'beneficiaire_ids.*'=> 'integer|exists:beneficiaire,id_beneficiaire',
        ]);

        $this->missionService->updateForAdmin(auth()->user(), $mission, $validated);

        // Sync bénéficiaires via pivot
        if (isset($validated['beneficiaire_ids'])) {
            $mission->beneficiaires()->sync($validated['beneficiaire_ids']);
        }

        // Handle bénévole assignment
        if (array_key_exists('benevole_id', $validated)) {
            $mission->benevole_id = $validated['benevole_id'];
            if ($validated['benevole_id'] && $validated['etat'] === 'non_prise') {
                $mission->etat_mission = 'prise';
            }
            if (!$validated['benevole_id'] && $validated['etat'] === 'prise') {
                $mission->etat_mission = 'non_prise';
            }
            $mission->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Mission mise à jour avec succès !',
        ]);
    }

    public function annuler(int $id): JsonResponse
    {
        if (!$this->isManager()) {
            return response()->json(['error' => 'Action non autorisée.'], 403);
        }

        $mission = Mission::where('id_mission', $id)->firstOrFail();
        $this->missionService->cancelMission($mission);

        return response()->json([
            'success' => true,
            'message' => 'Mission annulée.',
        ]);
    }

    public function prendre(int $id): JsonResponse
    {
        $mission = Mission::where('id_mission', $id)->firstOrFail();

        if ($mission->etat_mission !== 'non_prise' || $mission->benevole_id !== null) {
            return response()->json(['error' => 'Cette mission n\'est plus disponible.'], 422);
        }

        $mission->benevole_id = auth()->id();
        $mission->etat_mission = 'prise';
        $mission->save();

        return response()->json(['success' => 'Mission prise avec succès !']);
    }

    public function retirer(int $id): JsonResponse
    {
        $mission = Mission::where('id_mission', $id)->firstOrFail();

        if ((int) $mission->benevole_id !== (int) auth()->id()) {
            return response()->json(['error' => 'Vous ne pouvez pas retirer cette mission.'], 403);
        }

        $benevole = auth()->user();

        $mission->load('beneficiaires');

        $mission->benevole_id = null;
        $mission->etat_mission = 'non_prise';
        $mission->save();

        Mail::to('adjissiahmed2001@gmail.com')->send(new MissionRetraitMail($benevole, $mission));

        return response()->json(['success' => 'Mission retirée.']);
    }
}