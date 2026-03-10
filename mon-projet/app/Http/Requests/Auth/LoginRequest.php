<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        // If the account is still pending activation, the temporary password should only
        // be usable for a limited time window. After that, login must be refused so the
        // expiration concept keeps its meaning.
        $email = (string) $this->input('email');
        if ($email !== '') {
            $user = User::query()->with('profil')->where('email', $email)->first();
            if ($user && !$user->is_admin) {
                $profil = $user->profil;
                $isPendingActivation = $profil && isset($profil->est_valide) && !(bool) $profil->est_valide;

                if ($isPendingActivation && $this->isActivationWindowExpired($user)) {
                    throw ValidationException::withMessages([
                        'email' => 'Compte expiré : le délai d\'activation est dépassé. Merci de contacter un administrateur pour renvoyer une activation.',
                    ]);
                }
            }
        }

        // Security/UX: do not allow persistent "remember me" logins.
        // Sessions should expire based on SESSION_LIFETIME / SESSION_EXPIRE_ON_CLOSE.
        if (! Auth::attempt($this->only('email', 'password'), false)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    private function isActivationWindowExpired(User $user): bool
    {
        if (empty($user->created_at)) {
            return false;
        }

        $now = Carbon::now();

        // Activation link is implemented using the password reset broker.
        $resetExpireMinutes = (int) config('auth.passwords.users.expire', 60);
        if ($resetExpireMinutes <= 0) {
            $resetExpireMinutes = 60;
        }

        // Pending account expiration window (if configured).
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

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}
