<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // If the account is still pending activation and the activation window is expired,
        // do not send a new reset link (otherwise users could activate/reset “en retard”).
        $email = (string) $request->input('email');
        $user = User::query()->with('profil')->where('email', $email)->first();
        if ($user && !$user->is_admin) {
            $profil = $user->profil;
            $isPendingActivation = $profil && isset($profil->est_valide) && !(bool) $profil->est_valide;
            if ($isPendingActivation && $this->isActivationWindowExpired($user)) {
                return back()->withErrors([
                    'email' => 'Ce compte a dépassé le délai d\'activation. Merci de contacter un administrateur.',
                ]);
            }
        }

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status == Password::RESET_LINK_SENT
                    ? back()->with('status', __($status))
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
