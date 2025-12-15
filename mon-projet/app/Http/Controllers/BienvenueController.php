<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BienvenueController extends Controller
{
    /**
     * Affiche la page d'accueil publique du site.
     */
    public function index()
    {
        // 🔹 Nom de ton application (personnalisable)
        $nom = "Association Iroise";

        // 🔹 Retourne la vue 'bienvenue' avec le nom
        return view('bienvenue', compact('nom'));
    }
}
