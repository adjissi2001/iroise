<?php

namespace App\Http\Controllers;

use App\Models\Mission;
use Illuminate\View\View;

class AgendaController extends Controller
{
    /**
     * Affiche l'agenda des missions
     */
    public function index(): View
    {
        $missions = Mission::all();
        
        return view('agenda.index', [
            'missions' => $missions,
        ]);
    }
}
