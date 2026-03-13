<?php

use App\Models\Profil;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Password;

it('blocks login and password reset for expired pending (unvalidated) accounts', function () {
    // Make the pending expiration shorter than the reset-token expiration.
    config([
        'app.pending_user_expiration_minutes' => 5,
        'app.pending_user_expiration_hours' => 48,
        'auth.passwords.users.expire' => 60,
    ]);

    Carbon::setTestNow(Carbon::parse('2026-03-10 10:00:00'));

    $user = User::factory()->create([
        'created_at' => now()->subMinutes(6),
        'updated_at' => now()->subMinutes(6),
        'is_admin' => false,
    ]);

    Profil::create([
        'user_id' => $user->id,
        'nom' => 'Test',
        'prenom' => 'Pending',
        'date_naissance' => '2000-01-01',
        'adresse' => '1 rue de test',
        'code_postale' => '29200',
        'ville' => 'Brest',
        'role' => 'benevole',
        'est_valide' => 0,
        'actif' => 1,
    ]);

    // 1) Temporary password login must be refused after expiration window.
    $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ])->assertSessionHasErrors('email');

    $this->assertGuest();

    // 2) Requesting a new reset link must be refused too.
    $this->post('/forgot-password', [
        'email' => $user->email,
    ])->assertSessionHasErrors('email');

    // 3) Visiting the reset/activation form must redirect to login.
    $token = Password::broker()->createToken($user);

    $this->get('/reset-password/' . $token . '?email=' . urlencode($user->email))
        ->assertRedirect('/login')
        ->assertSessionHas('error');

    // 4) Submitting a reset must be refused.
    $this->post('/reset-password', [
        'token' => $token,
        'email' => $user->email,
        'password' => 'NewStrongPassword123!',
        'password_confirmation' => 'NewStrongPassword123!',
    ])->assertSessionHasErrors('email');
});
