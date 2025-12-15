<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AutoController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.auth');
    }

    // Traiter la soumission du formulaire
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Simulation d’un admin : à remplacer plus tard par ta table "users"
        if ($credentials['email'] === 'admin@iroise.fr' && $credentials['password'] === 'admin123') {
            // Stocker l’utilisateur en session
            session(['admin' => true]);
            return redirect('/administration')->with('success', 'Connexion réussie !');
        }

        return back()->withErrors(['email' => 'Identifiants incorrects.']);
    }
}
