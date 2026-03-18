<?php

namespace App\Http\Controllers;

use App\Models\Mission;
use Illuminate\View\View;

class CompteRenduController extends Controller
{
    /**
     * Affiche les comptes rendus des missions
     */
    public function index(): View
    {
        $missions = Mission::whereIn('etat_mission', ['effectuee', 'validee'])->get();
        
        return view('compte-rendu.index', [
            'missions' => $missions,
        ]);
    }
}
