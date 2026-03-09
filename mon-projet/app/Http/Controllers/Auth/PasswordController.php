<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $user = $request->user();

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        // Mark account active if supported
        try {
            if (isset($user->actif) && (int) ($user->actif ?? 0) === 0) {
                $user->actif = 1;
                $user->save();
            }
        } catch (\Throwable $e) {
            // ignore
        }

        // If the user's profil exists and was not validated, mark it validated
        try {
            $profil = $user->profil;
            if ($profil && isset($profil->est_valide) && !$profil->est_valide) {
                $profil->est_valide = 1;
                if (isset($profil->actif) && (int) ($profil->actif ?? 0) === 0) {
                    $profil->actif = 1;
                }
                $profil->save();
            }
        } catch (\Throwable $e) {
            // ignore
        }

        // Redirect to the appropriate landing page after activation
        $role = optional($user->profil)->role;

        if ($user->is_admin) {
            return redirect()->route('admin.beneficiaires')->with('success', 'Mot de passe modifié avec succès.');
        }

        return match ($role) {
            'benevole' => redirect()->route('benevole.index')->with('success', 'Mot de passe modifié avec succès.'),
            'referent' => redirect()->route('referent.index')->with('success', 'Mot de passe modifié avec succès.'),
            default => redirect()->route('dashboard')->with('success', 'Mot de passe modifié avec succès.'),
        };
    }
}
