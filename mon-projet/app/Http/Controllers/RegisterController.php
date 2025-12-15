<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RegisterController extends Controller
{
    /**
     * Affiche la page d'accueil publique du site.
     */
    public function inscriptionForm()
    {
         return view('welcome');
    }
}
