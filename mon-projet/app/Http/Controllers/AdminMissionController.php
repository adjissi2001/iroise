<?php

namespace App\Http\Controllers;

use App\Models\Mission;
use App\Services\MissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AdminMissionController extends Controller
{
    public function __construct(private readonly MissionService $missionService)
    {
    }

    public function index()
    {
        $missions = $this->missionService->listAllForAdmin();
        $categories = $this->missionService->listCategories();
        $beneficiaires = $this->missionService->listBeneficiairesForSelect();

        return view('admin.missions.index', compact('missions', 'categories', 'beneficiaires'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'categorie' => [
                'required',
                'integer',
                Rule::exists('categorie', 'id_categorie'),
            ],
            'lieu' => 'required|string|max:255',
            'date_depart' => 'required|date',
            'heure_depart' => ['required', 'regex:/^\d{2}:\d{2}(:\d{2})?$/'],
            'heure_arrivee' => ['required', 'regex:/^\d{2}:\d{2}(:\d{2})?$/'],
            'description' => 'required|string',
            'beneficiaires' => 'required|array|min:1',
            'beneficiaires.*' => [
                'integer',
                Rule::exists('beneficiaire', 'id_beneficiaire'),
            ],
        ]);

        $mission = $this->missionService->createForAdmin(auth()->user(), $validated);

        $redirectTo = (string) $request->input('redirect_to', '');
        $appUrl = rtrim(url('/'), '/');

        // Allow only internal redirects (avoid open redirect)
        $safeRedirect = null;
        if ($redirectTo !== '') {
            if (Str::startsWith($redirectTo, '/')) {
                $safeRedirect = $redirectTo;
            } elseif (Str::startsWith($redirectTo, $appUrl)) {
                $safeRedirect = $redirectTo;
            }
        }

        return redirect($safeRedirect ?: route('admin.missions.index'))
            ->with('success', 'Mission ajoutée (ID: '.($mission->id_mission ?? $mission->id).').');
    }

    public function edit(Mission $mission)
    {
        $categories = $this->missionService->listCategories();
        $beneficiaires = $this->missionService->listBeneficiairesForSelect();
        $selectedBeneficiaires = $this->missionService->getBeneficiaireIdsForMission($mission);

        return view('admin.missions.edit', compact('mission', 'categories', 'beneficiaires', 'selectedBeneficiaires'));
    }

    public function update(Request $request, Mission $mission)
    {
        $validated = $request->validate([
            'categorie' => [
                'required',
                'integer',
                Rule::exists('categorie', 'id_categorie'),
            ],
            'lieu' => 'required|string|max:255',
            'date_depart' => 'required|date',
            'heure_depart' => ['required', 'regex:/^\d{2}:\d{2}(:\d{2})?$/'],
            'heure_arrivee' => ['required', 'regex:/^\d{2}:\d{2}(:\d{2})?$/'],
            'description' => 'required|string',
            'etat' => 'required|in:non_prise,prise,validee,annulee',
            'beneficiaires' => 'required|array|min:1',
            'beneficiaires.*' => [
                'integer',
                Rule::exists('beneficiaire', 'id_beneficiaire'),
            ],
        ]);

        $this->missionService->updateForAdmin(auth()->user(), $mission, $validated);

        return redirect()->route('admin.missions.index')->with('success', 'Mission mise à jour.');
    }

    public function annuler(Mission $mission)
    {
        $this->missionService->cancelMission($mission);

        return redirect()->route('admin.missions.index')->with('success', 'Mission annulée.');
    }
}
