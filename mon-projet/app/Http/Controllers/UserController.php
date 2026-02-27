<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Profil;

class UserController extends Controller
{
    public function __construct(private readonly UserService $userService)
    {
    }

    /**
     * Affichage de la liste des utilisateurs
     */
    public function index()
    {
        $authUser = auth()->user();
        if (!$this->userService->canManageUsers($authUser)) {
            return redirect()->back()->with('error', 'Accès non autorisé.');
        }

        $validatedUsers = $this->userService->listValidated();
        $pendingUsers = $this->userService->listPending();

        return view('user.index', compact('validatedUsers', 'pendingUsers'));
    }

    /**
     * Affichage du détail d'un utilisateur
     */
    public function show($id)
    {
        $authUser = auth()->user();
        if (!$this->userService->canManageUsers($authUser)) {
            return redirect()->back()->with('error', 'Accès non autorisé.');
        }

        $user = $this->userService->find((int) $id);

        if (!$user) {
            abort(404);
        }

        return view('user.show', compact('user'));
    }

    /**
     * Affichage du formulaire d'édition (admin seulement)
     */
    public function edit($id)
    {
        $authUser = auth()->user();
        if (!$authUser->is_admin) {
            abort(403);
        }

        $user = $this->userService->find((int) $id);
        if (!$user) {
            abort(404);
        }

        return view('user.edit', compact('user'));
    }

    /**
     * Mise à jour de l'utilisateur (admin seulement)
     */
    public function update(Request $request, $id)
    {
        $authUser = auth()->user();
        if (!$authUser->is_admin) {
            abort(403);
        }

        $data = $request->validate([
            'email' => 'required|email',
            'prenom' => 'nullable|string|max:255',
            'nom' => 'nullable|string|max:255',
            'role' => 'nullable|string|max:50',
            'est_valide' => 'nullable|in:0,1',
        ]);

        $user = User::find($id);
        if (!$user) {
            abort(404);
        }

        $user->email = $data['email'];
        $user->save();

        $profil = $user->profil()->first();
        if (!$profil) {
            $profil = new Profil();
            $profil->user_id = $user->id;
        }
        $profil->prenom = $data['prenom'] ?? $profil->prenom;
        $profil->nom = $data['nom'] ?? $profil->nom;
        if (isset($data['role'])) {
            $profil->role = $data['role'];
        }
        if (isset($data['est_valide'])) {
            $profil->est_valide = (int) $data['est_valide'];
        }
        $profil->save();

        return redirect()->route('user.index')->with('success', 'Utilisateur mis à jour.');
    }

    /**
     * Suppression d'un utilisateur (admin seulement)
     */
    public function destroy($id)
    {
        $authUser = auth()->user();
        if (!$authUser->is_admin) {
            abort(403);
        }

        $user = User::find($id);
        if (!$user) {
            abort(404);
        }

        if ($user->id === $authUser->id) {
            return redirect()->route('user.index')->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        // supprimer les relations potentielles pour éviter les contraintes FK
        try { $user->profil()->delete(); } catch (\Throwable $e) {}
        try { $user->beneficiaires()->delete(); } catch (\Throwable $e) {}
        try { $user->voiture()->delete(); } catch (\Throwable $e) {}

        $user->delete();

        return redirect()->route('user.index')->with('success', 'Utilisateur supprimé.');
    }
}
