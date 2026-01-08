<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Affichage de la liste des utilisateurs
     */
    public function index()
    {
        // Vérifier que l'utilisateur est admin
        if (!auth()->user()->is_admin) {
            return redirect()->back()->with('error', 'Accès non autorisé.');
        }

        // Récupérer tous les utilisateurs
        $users = User::all();

        return view('user.index', compact('users'));
    }

    /**
     * Affichage du détail d'un utilisateur
     */
    public function show($id)
    {
        // Vérifier que l'utilisateur est admin
        if (!auth()->user()->is_admin) {
            return redirect()->back()->with('error', 'Accès non autorisé.');
        }

        $user = User::findOrFail($id);

        return view('user.show', compact('user'));
    }
}
