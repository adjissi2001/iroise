<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Profil;
use Illuminate\Support\Facades\DB;

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

        $pendingExpirationMinutes = (int) config('app.pending_user_expiration_minutes', 0);
        if ($pendingExpirationMinutes <= 0) {
            $pendingExpirationHours = (int) config('app.pending_user_expiration_hours', 48);
            if ($pendingExpirationHours <= 0) {
                $pendingExpirationHours = 48;
            }
            $pendingExpirationMinutes = $pendingExpirationHours * 60;
        }

        $pendingCutoff = Carbon::now()->subMinutes($pendingExpirationMinutes);
        $pendingExpiredCount = $pendingUsers->filter(function ($user) use ($pendingCutoff) {
            if (empty($user->created_at)) {
                return false;
            }
            try {
                return Carbon::parse($user->created_at)->lessThanOrEqualTo($pendingCutoff);
            } catch (\Throwable $e) {
                return false;
            }
        })->count();

        // LR/LAR (dans l'onglet "validés")
        $lrReferents = $this->userService->listReferentsActifsValidated();
        $larReferents = $this->userService->listAnciensReferentsValidated();

        return view('user.index', compact(
            'validatedUsers',
            'pendingUsers',
            'lrReferents',
            'larReferents',
            'pendingExpirationMinutes',
            'pendingCutoff',
            'pendingExpiredCount'
        ));
    }

    /**
     * Suppression manuelle des utilisateurs en attente dépassant le délai (admin seulement)
     */
    public function destroyExpiredPending(Request $request)
    {
        $authUser = auth()->user();
        if (!$authUser || !$authUser->is_admin) {
            abort(403);
        }

        $minutes = (int) config('app.pending_user_expiration_minutes', 0);
        if ($minutes <= 0) {
            $hours = (int) config('app.pending_user_expiration_hours', 48);
            if ($hours <= 0) {
                $hours = 48;
            }
            $minutes = $hours * 60;
        }

        $cutoff = Carbon::now()->subMinutes($minutes);

        $query = User::query()
            ->where('is_admin', 0)
            ->where('created_at', '<=', $cutoff)
            ->whereHas('profil', function ($q) {
                $q->where('est_valide', 0);
            });

        $deleted = 0;
        $failed = 0;

        $query->orderBy('id')->chunkById(200, function ($users) use (&$deleted, &$failed, $authUser) {
            foreach ($users as $user) {
                // Safety: never delete the current authenticated account.
                if ((int) $user->id === (int) $authUser->id) {
                    continue;
                }

                try {
                    DB::transaction(function () use ($user, &$deleted) {
                        try { $user->voiture()->delete(); } catch (\Throwable $e) {}
                        try { $user->profil()->delete(); } catch (\Throwable $e) {}
                        try { $user->beneficiaires()->update(['user_id' => null]); } catch (\Throwable $e) {}

                        $user->delete();
                        $deleted++;
                    });
                } catch (\Throwable $e) {
                    $failed++;
                }
            }
        });

        if ($deleted === 0 && $failed === 0) {
            return redirect()->route('user.index')->with('success', 'Aucun compte en attente n\'a dépassé le délai.');
        }

        if ($failed > 0) {
            return redirect()->route('user.index')->with('error', "Suppression partielle : {$deleted} supprimé(s), {$failed} échec(s).");
        }

        return redirect()->route('user.index')->with('success', "Suppression terminée : {$deleted} compte(s) expiré(s) supprimé(s).");
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

    /**
     * Active/désactive un utilisateur (admin seulement).
     */
    public function toggleActif(Request $request, $id)
    {
        $authUser = auth()->user();
        if (!$authUser || !$authUser->is_admin) {
            abort(403);
        }

        $targetId = (int) $id;
        if ($targetId === (int) $authUser->id) {
            return redirect()->route('user.index')->with('error', 'Vous ne pouvez pas désactiver votre propre compte.');
        }

        $user = $this->userService->find($targetId);
        if (!$user) {
            return redirect()->route('user.index')->with('error', 'Utilisateur introuvable.');
        }

        $current = (int) ($user->actif ?? optional($user->profil)->actif ?? 1);
        $nextIsActive = $current !== 1;

        $ok = $this->userService->toggleActif($targetId);
        if (!$ok) {
            return redirect()->route('user.index')->with('error', 'Impossible de modifier le statut de cet utilisateur.');
        }

        return redirect()->route('user.index')->with('success', $nextIsActive ? 'Utilisateur activé.' : 'Utilisateur désactivé.');
    }
}
