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

        // If the user's profil is not yet validated, force an immediate password change.
        // This enables the "1ère connexion" flow using the temporary password.
        if ($profil && isset($profil->est_valide) && !(bool) $profil->est_valide) {
            return redirect()
                ->route('profile.edit')
                ->with('warning', 'Pour activer votre compte, merci de modifier votre mot de passe.');
        }

        \Log::info('User Login Debug', [
            'user_id' => $user->id,
            'profil' => $profil,
            'role' => $role,
        ]);

        // Role-based landing page
        if ($user->is_admin) {
            return redirect()->intended(route('admin.beneficiaires', absolute: false));
        }

        return match ($role) {
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
