<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class LogoutIfDifferentResetEmail
{
    /**
     * If a user is already authenticated and opens a password reset/activation link
     * that targets a different email address, log out the current session.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $targetEmail = (string) $request->query('email', $request->input('email', ''));
            $currentEmail = (string) (Auth::user()->email ?? '');

            if ($targetEmail !== '' && $currentEmail !== '' && strcasecmp($targetEmail, $currentEmail) !== 0) {
                Auth::logout();

                $request->session()->invalidate();
                $request->session()->regenerateToken();
            }
        }

        return $next($request);
    }
}
