<?php

namespace App\Http\Controllers;

use App\Models\Mission;
use App\Services\MissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminMissionController extends Controller
{
    public function __construct(private readonly MissionService $missionService)
    {
    }

    public function index()
    {
        $missions = $this->missionService->listAllForAdmin();
        $categories = $this->missionService->listCategories();

        return view('admin.missions.index', compact('missions', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'categorie' => 'nullable|integer', // id de catégorie
            'lieu' => 'nullable|string|max:255',
            'date_depart' => 'required|date',
            'heure_depart' => 'nullable',
            'heure_arrivee' => 'nullable',
            'description' => 'nullable|string',
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

        return view('admin.missions.edit', compact('mission', 'categories'));
    }

    public function update(Request $request, Mission $mission)
    {
        $validated = $request->validate([
            'categorie' => 'nullable|integer', // id de catégorie
            'lieu' => 'nullable|string|max:255',
            'date_depart' => 'nullable|date',
            'heure_depart' => 'nullable',
            'heure_arrivee' => 'nullable',
            'description' => 'nullable|string',
            'etat' => 'required|in:non_prise,prise,validee,annulee',
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
