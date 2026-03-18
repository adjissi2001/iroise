<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Contracts\View\View;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function create(Request $request): View|RedirectResponse
    {
        $email = (string) $request->query('email', '');
        if ($email !== '') {
            $user = User::query()->with('profil')->where('email', $email)->first();
            if ($user && !$user->is_admin) {
                $profil = $user->profil;
                $isPendingActivation = $profil && isset($profil->est_valide) && !(bool) $profil->est_valide;
                if ($isPendingActivation && $this->isActivationWindowExpired($user)) {
                    return redirect()->route('login')
                        ->with('error', 'Lien expiré : le délai d\'activation est dépassé. Merci de contacter un administrateur.');
                }
            }
        }

        return view('auth.reset-password', ['request' => $request]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'temp_password' => ['required', 'string'], // Temporary password verification
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $email = (string) $request->input('email');
        $user = User::query()->with('profil')->where('email', $email)->first();
        if ($user && !$user->is_admin) {
            $profil = $user->profil;
            $isPendingActivation = $profil && isset($profil->est_valide) && !(bool) $profil->est_valide;
            if ($isPendingActivation && $this->isActivationWindowExpired($user)) {
                return back()->withInput($request->only('email'))
                    ->withErrors(['email' => 'Compte expiré : le délai d\'activation est dépassé. Merci de contacter un administrateur.']);
            }
        }

        // Verify temporary password
        if (!$user || !$user->temp_password) {
            return back()->withInput($request->only('email'))
                ->withErrors(['temp_password' => 'Mot de passe temporaire invalide ou expiré.']);
        }

        // Check if temp password is expired
        if ($user->temp_password_expires_at && now()->isAfter($user->temp_password_expires_at)) {
            return back()->withInput($request->only('email'))
                ->withErrors(['temp_password' => 'Le mot de passe temporaire a expiré. Merci de contacter un administrateur.']);
        }

        // Verify the temporary password matches
        if ($request->input('temp_password') !== $user->temp_password) {
            return back()->withInput($request->only('email'))
                ->withErrors(['temp_password' => 'Le mot de passe temporaire est incorrect.']);
        }

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                    'temp_password' => null, // Clear temporary password after successful reset
                    'temp_password_expires_at' => null,
                ])->save();

                // Activate the account after the password is set via the activation link.
                try {
                    if (isset($user->actif) && (int) ($user->actif ?? 0) === 0) {
                        $user->actif = 1;
                        $user->save();
                    }
                } catch (\Throwable $e) {
                    // Non critique
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
                    // Non critique: ne pas empêcher la réinitialisation du mot de passe
                }

                event(new PasswordReset($user));
            }
        );

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        return $status == Password::PASSWORD_RESET
                    ? redirect()->route('login')->with('status', __($status))
                    : back()->withInput($request->only('email'))
                        ->withErrors(['email' => __($status)]);
    }

    private function isActivationWindowExpired(User $user): bool
    {
        if (empty($user->created_at)) {
            return false;
        }

        $now = Carbon::now();

        $resetExpireMinutes = (int) config('auth.passwords.users.expire', 60);
        if ($resetExpireMinutes <= 0) {
            $resetExpireMinutes = 60;
        }

        $pendingMinutes = (int) config('app.pending_user_expiration_minutes', 0);
        if ($pendingMinutes <= 0) {
            $pendingHours = (int) config('app.pending_user_expiration_hours', 48);
            if ($pendingHours <= 0) {
                $pendingHours = 48;
            }
            $pendingMinutes = $pendingHours * 60;
        }

        $activationWindowMinutes = min($resetExpireMinutes, $pendingMinutes);

        try {
            return Carbon::parse($user->created_at)->lessThanOrEqualTo($now->copy()->subMinutes($activationWindowMinutes));
        } catch (\Throwable $e) {
            return false;
        }
    }
}
