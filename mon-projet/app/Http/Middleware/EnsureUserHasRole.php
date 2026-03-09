<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Ensure the authenticated user has the given profil.role.
     * Usage: ->middleware('role:benevole')
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->user();
        $currentRole = optional($user?->profil)->role;

        if (!$user || $currentRole !== $role) {
            // Keep it simple: deny access to role-specific spaces.
            abort(403, 'Accès refusé.');
        }

        return $next($request);
    }
}
