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

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        // If the user's profil exists and was not validated, mark it validated
        try {
            $user = $request->user();
            $profil = $user->profil;
            if ($profil && isset($profil->est_valide) && !$profil->est_valide) {
                $profil->est_valide = 1;
                $profil->save();
            }
        } catch (\Throwable $e) {
            // ignore
        }

        return redirect()->route('dashboard')->with('success', 'Mot de passe modifié avec succès.');
    }
}
