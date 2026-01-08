<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Profil;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
  


    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            // Auth (table users)
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],

            // Métier (profil)
            'prenom' => ['required', 'string', 'max:100'],
            'nom' => ['required', 'string', 'max:100'],
            'date_naissance' => ['required', 'date'],
            'num_tel' => ['nullable', 'string', 'max:20'],
            'role_profil' => ['required', 'in:referent,benevole,bienfaiteur'],
        ]);

        $user = null;

        DB::transaction(function () use ($request, &$user) {
            // 1) Create user (auth)
            $user = User::create([
                'name' => $request->prenom . ' ' . $request->nom,
                'email' => $request->email,
                'password' => Hash::make($request->password),

                // tes champs ajoutés dans users
                'role' => 'user',
                'actif' => 1,
            ]);

            // 2) Create profil (métier) lié à users.id
            Profil::create([
                'user_id' => $user->id,
                'nom' => $request->nom,
                'prenom' => $request->prenom,
                'date_naissance' => $request->date_naissance,
                'num_tel' => $request->num_tel,
                'role' => $request->role_profil,
                'est_valide' => 0,
                'actif' => 1,
            ]);

        });

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }

}
