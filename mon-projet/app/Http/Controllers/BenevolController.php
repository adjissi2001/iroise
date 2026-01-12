<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BenevolController extends Controller
{
    /**
     * Affichage de l'espace des bénévoles
     */
    public function index()
    {
        return view('benevole.index');
    }
}
