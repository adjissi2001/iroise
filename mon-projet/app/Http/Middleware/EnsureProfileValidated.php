<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;

class EnsureProfileValidated
{
	/**
	 * Force first-login password change until profil.est_valide == 1.
	 */
	public function handle(Request $request, Closure $next): Response
	{
		$user = $request->user();

		// If there is no authenticated user, nothing to enforce.
		if (!$user) {
			return $next($request);
		}

		$profil = $user->profil;

		// If the user has no profil record, do not block.
		if (!$profil) {
			return $next($request);
		}

		// If already validated, allow.
		if ((int) ($profil->est_valide ?? 1) === 1) {
			return $next($request);
		}

		$routeName = $request->route()?->getName();

		// Whitelist routes that must remain accessible while unvalidated.
		$allowedRouteNames = [
			'profile.edit',
			'profile.update',
			'password.update',
			'password.confirm',
			'logout',
		];

		if ($routeName && in_array($routeName, $allowedRouteNames, true)) {
			return $next($request);
		}

		if ($routeName && Str::startsWith($routeName, 'verification.')) {
			return $next($request);
		}

		// Path-based fallback (in case route names differ)
		if (
			$request->is('profile') ||
			$request->is('profile/*') ||
			$request->is('password') ||
			$request->is('logout') ||
			$request->is('verify-email') ||
			$request->is('verify-email/*') ||
			$request->is('email/verification-notification')
		) {
			return $next($request);
		}

		return redirect()
			->route('profile.edit')
			->with('warning', 'Vous devez modifier votre mot de passe à votre première connexion pour accéder au système.');
	}
}
