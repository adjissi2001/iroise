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
            'num_tel' => ['nullable', 'regex:/^\d{10}$/'],

            'adresse' => ['required', 'string', 'max:255'],
            'ville' => ['required', 'string', 'max:100'],
            'code_postale' => ['required', 'regex:/^\d{5}$/'],
            'num_fixe' => ['nullable', 'regex:/^\d{10}$/'],

            'role_profil' => ['required', 'in:referent,benevole,bienfaiteur'],
        ], [
            'prenom.required' => 'Le prénom est obligatoire.',
            'nom.required' => 'Le nom est obligatoire.',
            'date_naissance.required' => 'La date de naissance est obligatoire.',
            'email.required' => 'L\'email est obligatoire.',
            'email.email' => 'Merci de saisir une adresse email valide.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
            'adresse.required' => 'L\'adresse est obligatoire.',
            'ville.required' => 'La ville est obligatoire.',
            'code_postale.required' => 'Le code postal est obligatoire.',
            'code_postale.regex' => 'Le code postal doit contenir 5 chiffres.',
            'num_tel.regex' => 'Le téléphone doit contenir 10 chiffres.',
            'num_fixe.regex' => 'Le numéro fixe doit contenir 10 chiffres.',
            'role_profil.required' => 'Le rôle est obligatoire.',
        ]);

        $user = null;

        DB::transaction(function () use ($request, &$user) {
            // 1) Create user (auth)
            $user = User::create([
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'actif' => 1,
            ]);

            // 2) Create profil (métier) lié à users.id
            Profil::create([
                'user_id' => $user->id,
                'nom' => $request->nom,
                'prenom' => $request->prenom,
                'date_naissance' => $request->date_naissance,
                'num_tel' => $request->num_tel,
                'num_fixe' => $request->num_fixe,
                'adresse' => $request->adresse,
                'code_postale' => $request->code_postale,
                'ville' => $request->ville,
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
