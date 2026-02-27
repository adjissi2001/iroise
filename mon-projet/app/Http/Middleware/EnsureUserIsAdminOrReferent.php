<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsAdminOrReferent
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')->withErrors(['auth' => 'Vous devez être connecté.']);
        }

        $user = Auth::user();

        $isReferent = optional($user->profil)->role === 'referent';

        if (!($user->is_admin || $isReferent)) {
            abort(403, 'Accès réservé aux administrateurs et référents.');
        }

        return $next($request);
    }
}
