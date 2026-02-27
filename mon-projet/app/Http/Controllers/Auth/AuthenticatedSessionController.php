<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Redirection selon le rôle dans la table profil
        $user = auth()->user();
        $profil = $user->profil;
        $role = $profil ? $profil->role : null;

        // If the user's profil is not yet validated, show a banner on their dashboard
        // instructing them to change their password (do not redirect to profile directly).
        if ($profil && isset($profil->est_valide) && !$profil->est_valide) {
            $request->session()->flash('must_update_password', 'Le mot de passe doit être modifié pour accéder aux fonctionnalités du système. Cliquez ici pour modifier.');
        }

        \Log::info('User Login Debug', [
            'user_id' => $user->id,
            'profil' => $profil,
            'role' => $role,
        ]);

        return match($role) {
            'admin' => redirect()->intended(route('admin.beneficiaires', absolute: false)),
            'benevole' => redirect()->intended(route('benevole.index', absolute: false)),
            'referent' => redirect()->intended(route('referent.index', absolute: false)),
            default => redirect()->intended(route('dashboard', absolute: false)),
        };
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
